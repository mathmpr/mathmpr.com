class CropperModal {

    static container = $('#cropper-modal');
    static confirm = false;
    static options = {};

    static open(options = {}, onClose = false, onDismiss) {
        if (typeof options !== 'object') {
            options = {};
        }
        CropperModal.onClose = false;
        CropperModal.options = options;
        CropperModal.init();
        if (typeof onClose === 'function') {
            CropperModal.onClose = onClose;
        }
        if (typeof onDismiss === 'function') {
            CropperModal.onDismiss = onDismiss;
        }
        CropperModal.container.modal('show');
    }

    static reset() {
        CropperModal.container.find('#cropper-modal .target').html('');
    }

    static init() {

        new Cropper({
            target: '#cropper-modal .target',
            image: CropperModal.options.image,
            frame: CropperModal.options.frame,
            onFinish: CropperModal.options.onFinish,
            maxFrameWidth: 450
        })

        CropperModal.container.unbind('shown.bs.modal');
        CropperModal.container.on('shown.bs.modal', () => {

        });

        CropperModal.container.unbind('hide.bs.modal');
        CropperModal.container.on('hide.bs.modal', () => {
            if (CropperModal.confirm) {
                let selections = []
                CropperModal.content.find('.selected').each((index, el) => {
                    selections.push($(el).closest('.col-2').get(0)['selected']);
                });
                if (CropperModal.onClose && typeof CropperModal.onClose === 'function') {
                    CropperModal.onClose(selections);
                }
            } else if (CropperModal.onDismiss && typeof CropperModal.onDismiss === 'function') {
                CropperModal.onDismiss();
            }
            CropperModal.reset();
        });
    }

}

module.exports = CropperModal;
