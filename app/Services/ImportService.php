<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Repositories\Backend\BaseCategoryRepository;
use App\Repositories\ImportLog\ImportLogRepository;
use App\Repositories\Post\PostRepository;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ImportService
{
    protected $postRepo;
    protected $catRepo;
    protected $importRepo;

    public function __construct(
        PostRepository         $postRepo,
        Post                   $postModel,
        PostTranslation        $modelTranslation,
        BaseCategoryRepository $catRepo,
        Category               $catModel,
        ImportLogRepository    $importRepo
    )
    {
        $this->postRepo = $postRepo;
        $this->postRepo->addModel($postModel);
        $this->postRepo->setModelTranslate($modelTranslation);
        $this->catRepo = $catRepo;
        $this->catRepo->addModel($catModel);
        $this->importRepo = $importRepo;
    }

    public function import(array $params): array
    {
        $result = [];
        $categoryId = $params['category_id'];
        $category = $this->catRepo->getDetailById($categoryId);

        // create import log data
        $file = $params['file'];
        $dataLog = [
            'file_name' => $file->getClientOriginalName(),
            'category_id' => $categoryId,
            'items_count' => 0,
            'imported_at' => Carbon::now(),
            'status' => IMPORT_STATUS['IMPORTING']
        ];
        $importLog = $this->importRepo->create($dataLog);

        $data = Excel::toArray((object)null, $file);
        $data = reset($data);
        unset($data[0]);

        if (count($data) == 0) {
            $dataUpdateLog = [
                'status' => IMPORT_STATUS['FAIL'],
            ];
            $this->importRepo->update($importLog->id, $dataUpdateLog);

            return [
                'success' => false,
                'message' => "Dữ liệu rỗng"
            ];
        }

        // create card data
        $dataPost = $checkLatLong = [];
        $count = 0;
        foreach (array_chunk($data, 100) as $items) {
            foreach ($items as $item) {
                $checkLatLong[] = $item[3] . '-' . $item[4];
                $dataPost[] = [
                    'title' => $item[0] . ' - ' . $item[1],
                    'category_id' => $categoryId,
                    'phone_number' => $item[2],
                    'lat' => $item[3],
                    'long' => $item[4],
                    'feature_image' => !empty($item[5]) ? $item[5] : '/default/default_image.jpg',
                    'import_log_id' => $importLog->id
                ];
                $count++;
            }
        }

        // remove duplicates 2 rows
        foreach ($checkLatLong as $key => $value) {
            if (isset($checkLatLong[$key - 1]) && $value === $checkLatLong[$key - 1]) {
                unset($dataPost[$key]);
                $count--;
            }
        }

        // remove existed in DB (!need optimize when check card existed)
        foreach ($dataPost as $idx => $data) {
            if ($this->postRepo->checkLatLongExisted($data['lat'], $data['long'], $categoryId)) {
                unset($dataPost[$idx]);
            }
        }

        if (count($dataPost) == 0) {
            $dataUpdateLog = [
                'status' => IMPORT_STATUS['FAIL'],
                'items_count' => $count
            ];
            $this->importRepo->update($importLog->id, $dataUpdateLog);
            return [
                'success' => false,
                'message' => 'Dữ liệu bị trùng lặp'
            ];
        } else {
            foreach (array_chunk($dataPost, 100) as $posts) {
                foreach ($posts as $item) {
                    $data = $this->postRepo->create($item);
                    $dataItem = $data->toArray();
                    $dataItem['category_name'] = $category->title;
                    $result[] = $dataItem;
                    $data = $this->postRepo->createTranslation([
                        'en' => [
                            'title' => '',
                            'description' => '',
                            'content' => '',
                        ]
                    ], $data->id);
                }
            }

            // update import log when success
            $dataUpdateLog = [
                'status' => IMPORT_STATUS['SUCCESS'],
                'items_count' => $count,
                'data' => json_encode($result),
                'imported_at' => Carbon::now()
            ];
            $this->importRepo->update($importLog->id, $dataUpdateLog);

            return [
                'success' => true,
                'data' => $result,
                'message' => 'Thêm dữ liệu thành công'
            ];
        }
    }

    public function deleteAllPostByLogId(int $id)
    {
        $this->postRepo->deleteByImportLogId($id);
        $this->importRepo->update($id, ['status' => IMPORT_STATUS['DELETED']]);
    }
}