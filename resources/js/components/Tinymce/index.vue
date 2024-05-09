<template>
    <div class="tinymce-container">
        <textarea v-bind:id="tinymceId" class="tinymce-textarea" />
    </div>
</template>

<script>
	import { loadScript } from "vue-plugin-load-script";
    export default {
        name: 'Tinymce',

        props: {
            id: {
                type: String,
                default: function () {
                    return 'vue-tinymce-' + +new Date() + ((Math.random() * 1000).toFixed(0) + '')
                }
            },
            value: {
                type: String,
                default: ''
            },

            height: {
                type: [Number, String],
                required: false,
                default: 460
            },
            width: {
                type: [Number, String],
                required: false,
                default: 'auto'
            }
        },

        watch: {
            value(val) {
                if (!this.hasChange && this.hasInit) {
                    this.$nextTick(() => window.tinymce.get(this.tinymceId).setContent(val || ''))
                }
            }
        },

        mounted() {
            this.init()
        },

        data() {
            return {
                hasChange: false,
                hasInit: false,
                tinymceId: this.id,
            }
        },

        methods: {
            init() {
                loadScript("https://cdn.tiny.cloud/1/ds3lhvs59zp4955ao2lkpjr48fz86cf0sad77krao880u6rw/tinymce/5/tinymce.min.js")
                    .then(() => {
                        this.initTinymce()
                    })
                    .catch(() => {
                        console.log('failed to load');
                    });
            },

            initTinymce() {
                const _this = this
                window.tinymce.init({
                    selector: `#${this.tinymceId}`,
                    height: this.height,
                    menubar: true,
                    plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap  emoticons',
                    tinydrive_token_provider: 'URL_TO_YOUR_TOKEN_PROVIDER',
                    tinydrive_dropbox_app_key: 'lc6vthuk06ohwxs',
                    tinydrive_google_drive_key: 'YOUR_GOOGLE_DRIVE_KEY',
                    tinydrive_google_drive_client_id: 'YOUR_GOOGLE_DRIVE_CLIENT_ID',
                    imagetools_cors_hosts: ['picsum.photos'],
                    // menubar: 'file edit view insert format tools table help',
                    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | image  |  forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile media template link anchor codesample | ltr rtl',
                    toolbar_sticky: true,
                    autosave_ask_before_unload: true,
                    autosave_interval: "30s",
                    autosave_prefix: "{path}{query}-{id}-",
                    autosave_restore_when_empty: false,
                    autosave_retention: "2m",
                    image_advtab: true,
                    content_css: [
                        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                        '//www.tiny.cloud/css/codepen.min.css'
                    ],
                    link_list: [
                        {title: 'My page 1', value: 'http://www.tinymce.com'},
                        {title: 'My page 2', value: 'http://www.moxiecode.com'}
                    ],
                    image_list: [
                        {title: 'My page 1', value: 'http://www.tinymce.com'},
                        {title: 'My page 2', value: 'http://www.moxiecode.com'}
                    ],
                    image_class_list: [
                        {title: 'None', value: ''},
                        {title: 'Some class', value: 'class-name'}
                    ],
                    importcss_append: true,
                    file_picker_callback: function (callback, value, meta) {
                        /* Provide file and text for the link dialog */
                        if (meta.filetype === 'file') {
                            callback('https://www.google.com/logos/google.jpg', {text: 'My text'});
                        }

                        /* Provide image and alt text for the image dialog */
                        if (meta.filetype === 'image') {

                            var finder = new CKFinder();

                            finder.selectActionFunction = function (fileUrl, data) {
                                callback(fileUrl, {text: ''});
                            };

                            finder.popup();

                        }

                        /* Provide alternative source and posted for the media dialog */
                        if (meta.filetype === 'media') {
                            callback('movie.mp4', {
                                source2: 'alt.ogg',
                                poster: 'https://www.google.com/logos/google.jpg'
                            });
                        }
                    },
                    templates: [
                        {
                            title: 'New Table',
                            description: 'creates a new table',
                            content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
                        },
                        {
                            title: 'Starting my story',
                            description: 'A cure for writers block',
                            content: 'Once upon a time...'
                        },
                        {
                            title: 'New list with dates',
                            description: 'New List with dates',
                            content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
                        }
                    ],
                    template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
                    template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
                    image_caption: true,
                    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                    noneditable_noneditable_class: "mceNonEditable",
                    toolbar_mode: 'sliding',
                    contextmenu: "link image imagetools table",

                    init_instance_callback: editor => {
                        if (_this.value) {
                            editor.setContent(_this.value)
                        }
                        _this.hasInit = true
                        editor.on('NodeChange Change KeyUp SetContent', () => {
                            this.hasChange = true
                            this.$emit('input', editor.getContent())
                        })
                    }
                })
            },

            setContent(value) {
                window.tinymce.get(this.tinymceId).setContent(value)
            },

            getContent() {
                window.tinymce.get(this.tinymceId).getContent()
            },
        }
    }
</script>

<style lang="scss" scoped>
    .tinymce-container {
        position: relative;
        line-height: normal;
        clear: both;
    }
    
    .tinymce-container .mce-fullscreen {
        z-index: 10000;
    }
    
    .tinymce-textarea {
        visibility: hidden;
        z-index: -1;
    }
    
    .editor-custom-btn-container {
        position: absolute;
        right: 4px;
        top: 4px;
        z-index: 2005;
    }
    
    .fullscreen .editor-custom-btn-container {
        z-index: 10000;
        position: fixed;
    }
    
    .editor-upload-btn {
        display: inline-block;
    }
</style>