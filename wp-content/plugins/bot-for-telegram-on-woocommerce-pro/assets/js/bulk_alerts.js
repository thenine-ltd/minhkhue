"use strict";
document.addEventListener("DOMContentLoaded", ready);

function ready() {

    /**
     * @var bftow_pro_bulk_alerts
     */

    new Vue({
        el: '#bftow_bulk_upload',
        data: {
            ajax_url: bftow_pro_bulk_alerts['ajax_url'],
            newMessage: '',
            image: {
                id: 0,
                url: ''
            },
            message: '',
            loading: '',
            translations: bftow_pro_bulk_alerts['translations'],
            records: bftow_pro_bulk_alerts['records'],
        },
        methods: {
            continueMessages(record) {
                return parseInt(record.current_users) < parseInt(record.total_users);
            },
            selectImage() {
                var _this = this;
                var image_frame;
                image_frame = wp.media({
                    title: 'Select Media',
                    multiple: false,
                    library: {
                        type: 'image',
                    }
                });

                image_frame.open();

                image_frame.on('close', function () {
                    // On close, get selections and save to the hidden input
                    // plus other AJAX stuff to refresh the image preview
                    var selection = image_frame.state().get('selection').first().toJSON();

                    _this.$set(_this.image, 'id', selection.id);
                    _this.$set(_this.image, 'url', selection.sizes.full['url']);
                });
            },
            deleteImage() {
                this.$set(this, 'image', {});
            },
            hasImage() {
                return this.image.id;
            },
            hasText() {
                return this.newMessage.length;
            },
            hasMessage() {
                return (this.hasImage() || this.hasText());
            },
            createRecord() {
                var _this = this;
                _this.loading = true;

                _this.message = _this.translations['creating'];

                _this.$http.post(_this.ajax_url + '?action=bftow_pro_create_new_record', {
                    message: _this.newMessage,
                    image_id: _this.image['id']
                }).then(function (r) {
                    var r = r.body;
                    _this.$set(_this, 'message', r['message']);
                    _this.loading = false;

                    if (r['error']) return false;

                    _this.$set(_this, 'image', {id: 0, url: ''});
                    _this.newMessage = '';
                    _this.$set(_this, 'records', r['records']);

                    _this.sendMessage(0);
                    _this.message = _this.translations['sending'];

                })
            },
            sendMessage(key) {
                var _this = this;
                _this.$http.get(_this.ajax_url + '?action=bftow_pro_send_single_bulk_message' +
                '&record_id=' + _this.records[key]['id']).then(function(r){
                    r = r.body;
                    _this.message = r.message;
                    _this.$set(_this, 'records', r['records']);
                    if(r.next) this.sendMessage(key);
                });
            }
        }
    });

}