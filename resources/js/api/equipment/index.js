import request from "@/utils/request";

const resource = "/user/equipment";

export default {
    listInventoryItems(query){
        return request({
            url: `${resource}` + "/list-inventory-items",
            method: 'get',
            params: query
        })
    },
    listEquippedItems(query){
        return request({
            url: `${resource}` + "/list-equipped-items",
            method: 'get',
            params: query
        })
    },
    removeEquippedItem(ueId) {
        let data = {
            ue_id: ueId
        };
        return request({
            url: `${resource}` + "/remove-equipped-item",
            method: 'post',
            data
        })
    },
    useEquipment(ueId, pos) {
        let data = {
            ue_id: ueId,
            position: pos
        };
        return request({
            url: `${resource}` + "/use-equipment",
            method: 'post',
            data
        })
    }
};
