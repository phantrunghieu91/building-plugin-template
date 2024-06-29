export default class UpdateImageButton {
  protected _uploadBtns: NodeListOf<HTMLButtonElement>;
  protected _removeImgBtns : NodeListOf<HTMLAnchorElement>;
  protected _wp: any;

  constructor(wp: any) {
    this._uploadBtns = document.querySelectorAll('.image-upload-btn');
    this._removeImgBtns = document.querySelectorAll('.remove-selected-img');
    this._wp = wp;
    this._uploadBtns.forEach(btn => {
      btn.addEventListener('click', this.handleUploadBtnClick);
    });
    this._removeImgBtns.forEach(btn => {
      btn.addEventListener('click', this.handleRemoveImgClick);
    });
  }

  handleUploadBtnClick: (event: MouseEvent) => void = clickEvent => {
    clickEvent.preventDefault();
    const _self: HTMLButtonElement = clickEvent.currentTarget as HTMLButtonElement;
    const imageInput: HTMLInputElement = _self.previousElementSibling as HTMLInputElement;
    let imagePreview: HTMLImageElement | null = _self.nextElementSibling as HTMLImageElement;
    if (typeof this._wp !== 'undefined') {
      const wpMedia = this._wp.media({
        title: 'Upload Image',
        multiple: false,
      });
      wpMedia.on('select', function () {
        const attachment = wpMedia.state().get('selection').first().toJSON();
        this.changeInputValue(imageInput, attachment.url);
        if (imagePreview) imagePreview.src = attachment.url;
        else {
          imagePreview = document.createElement('img');
          imagePreview.classList.add('image-preview');
          imagePreview.src = attachment.url;
          _self.insertAdjacentElement('afterend', imagePreview);
        }
      });
      wpMedia.open();
    }
  };
  handleRemoveImgClick: (event: MouseEvent) => void = clickEvent => {
    clickEvent.preventDefault();
    const _self: HTMLAnchorElement = clickEvent.currentTarget as HTMLAnchorElement;
    const imageInput: HTMLInputElement = _self.nextElementSibling as HTMLInputElement;
    const imagePreview: HTMLImageElement = imageInput.nextElementSibling.nextElementSibling as HTMLImageElement;
    this.changeInputValue(imageInput, '');
    imagePreview.remove();
  };
  protected changeInputValue(input: HTMLInputElement, value: string) {
    input.value = value;
    input.dispatchEvent(new Event('input', { bubbles: true }));
  }
}
