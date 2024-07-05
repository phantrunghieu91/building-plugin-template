document.addEventListener('DOMContentLoaded', function (domEvent) {
  class JinsPluginAuth {
    private _showAuthDialogBtn: HTMLAnchorElement | null;
    private _authDialog: HTMLDialogElement | null;
    private _authForm: HTMLFormElement | null;
    private _closeDialogBtn: HTMLAnchorElement | null;
    private _formMessage: HTMLDivElement | null;
    constructor() {
      this._showAuthDialogBtn = document.querySelector('.jins-auth__show-auth-form-btn');
      this._authDialog = document.querySelector('.jins-auth__dialog');
      this._authForm = document.querySelector('.jins-auth__form');
      this._closeDialogBtn = document.querySelector('.jins-auth__close-dialog-btn');
      this._formMessage = this._authForm?.querySelector('.jins-auth__form-message');

      this._init();
    }
    private _init() {
      this._showAuthDialogBtn?.addEventListener('click', this._showAuthDialog);
      this._authForm?.addEventListener('submit', this._submitAuthForm);
      this._closeDialogBtn?.addEventListener('click', this._hideAuthDialog);
    }
    private _showAuthDialog = (event: MouseEvent) => {
      event.preventDefault();
      if (this._authDialog) {
        this._authDialog.showModal();
      }
    }
    private _hideAuthDialog = (event: MouseEvent | null) => {
      event.preventDefault();
      if (this._authDialog) {
        this._authDialog.close();
      }
    }
    private _submitAuthForm = async (event: SubmitEvent) => {
      event.preventDefault();
      const url = new URL(this._authForm?.dataset.url);
      const formData = new FormData(this._authForm);
      try {
        const res = await fetch(url.toString(), {
          method: 'POST',
          body: formData,
        });
        const data = await res.json();
        if(!res.ok) {
          throw new Error(data.message);
        }
        if (this._formMessage) {
          this._formMessage.innerHTML = data.message;
          this._formMessage.classList.remove('jins-auth__form-message--error', 'jins-auth__form-message--success');
          this._formMessage.classList.add(`jins-auth__form-message--${data.status}`);
        }
        if (data.status === 'success') {
          window.location.reload();
          setTimeout(() => {
            this._hideAuthDialog(null);
          }, 1000);
        }
      } catch (error) {
        console.error("Error when trying to log in: ", error);
      }
    }
  }
  const jinsPluginAuth = new JinsPluginAuth();
});
