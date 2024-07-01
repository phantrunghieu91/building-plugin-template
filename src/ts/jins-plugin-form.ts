document.addEventListener('DOMContentLoaded', function(domEvent) {
  // Handle jins-plugin-form submission with object literal
  (function() {
    const classPrefix = 'jins-plugin-form__';
    function isFieldValid (fieldName: string, formData: FormData) : boolean {
      let result = true;
      const fieldValue = formData.get(fieldName);
      if(fieldValue === '') result = false;
      if(fieldName === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        result = emailRegex.test((fieldValue as string).toLowerCase());
      } 
      // console.log(fieldName, fieldValue, result);
      return result;
    }
    return {
      jinsPluginForm: document.querySelector('.jins-plugin-form') as HTMLFormElement | null,
      formMessage: document.querySelector('.jins-plugin-form__form-message') as HTMLDivElement | null,
      init: function () {
        if( this.jinsPluginForm === null ) return;
        this.jinsPluginForm.addEventListener('submit', async (event: Event) => {
          // 'this' inside this form is referring to the form itself
          event.preventDefault();
          this.changeFormMessage();
          this.hideAllFieldErrorMessages();
          const formData = new FormData(this.jinsPluginForm);
          // validate the form data
          const isNameValid = isFieldValid('name', formData);
          const isEmailValid = isFieldValid('email', formData);
          const isMessageValid = isFieldValid('message', formData);

          if(!isNameValid) {
            this.displayFieldErrorMessage('name', 'Please enter a valid name');
          }
          if(!isEmailValid) {
            this.displayFieldErrorMessage('email', 'Please enter a valid email address');
          }
          if(!isMessageValid) {
            this.displayFieldErrorMessage('message', 'Please enter a valid message');
          }
          // if all fields are valid, submit the form
          if(!( isNameValid && isEmailValid && isMessageValid )) {
            this.changeFormMessage('error', 'Please fill in the missing fields in the form');
            return;
          }
          this.changeFormMessage('loading', 'Sending your message...');
          // send the form data to the server
          try {
            const url = this.jinsPluginForm.dataset.url;
            if(url === undefined) {
              this.changeFormMessage('error', 'Invalid form URL');
              return;
            }
            const response = await fetch(new URL(url), {
              method: 'POST',
              body: formData
            });
            const responseData = await response.json();
            // Handle the response
            if(!response.ok || !responseData.success) {
              this.changeFormMessage('error', responseData.data);
              return;
            }
            this.changeFormMessage('success', responseData.data);
            this.jinsPluginForm.reset();
          } catch (error) {
            console.error('An error occurred while sending the form data:', error);
            this.changeFormMessage('error', 'An error occurred while sending your message');
          }
        });
      },
      changeFormMessage: function(state: 'success' | 'error' | 'loading' | 'null' = 'null', message: string | '' = '' ) : void {
        this.formMessage.className = `${classPrefix}form-message`;

        this.formMessage.textContent = '';
        if(!(state === 'null' && message === '')) {
          this.formMessage.classList.add(`${classPrefix}form-message--${state}`);
          this.formMessage.textContent = message;
        }
      },
      displayFieldErrorMessage: function(fieldName: string, message: string) {
        const field: HTMLInputElement | HTMLTextAreaElement = this.jinsPluginForm.querySelector(`[name=${fieldName}]`);
        const errorMessageEle = field.nextElementSibling as HTMLSpanElement;
        if(field === null) return;
        errorMessageEle.textContent = message;
        errorMessageEle.classList.add(`${classPrefix}field-error-message--show`);
      },
      hideAllFieldErrorMessages: function() {
        const fieldErrorMessages: NodeListOf<HTMLSpanElement> = this.jinsPluginForm.querySelectorAll(`.${classPrefix}field-error-message`);
        fieldErrorMessages.forEach((fieldErrorMessage) => {
          fieldErrorMessage.classList.remove(`${classPrefix}field-error-message--show`);
        });
      }
    };
  })().init();
});