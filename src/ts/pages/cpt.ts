export const customPostType = {
  editBtns: document.querySelectorAll('.edit-cpt') as NodeListOf<HTMLAnchorElement>,
  init: function () {
    this.editBtns.forEach((editBtn: HTMLAnchorElement) => {
      editBtn.addEventListener('click', this.handleEditBtnClick);
    });
  },
  handleEditBtnClick: function (clickEvent: MouseEvent) {
    clickEvent.preventDefault();

    // Move to modify-cpt tab
    const currentActiveNav: HTMLAnchorElement = document.querySelector('.nav__item--active');
    currentActiveNav.classList.remove('nav__item--active');
    const modifyNav: HTMLAnchorElement = document.querySelector('.nav__item[data-target="#modify-cpt"');
    modifyNav.classList.add('nav__item--active');
    modifyNav.innerHTML = `Editing <strong>${this.dataset.singularName}</strong> Post Type`;
    const tabContent: HTMLElement = document.querySelector('.tab-content');
    tabContent?.style.setProperty('--tab-translate-x', `-${(1 / 3) * 100}%`);

    // Populate the form with the data when clicked
    const dataNames: Object = { postType: 'post_type', singularName: 'singular_name', pluralName: 'plural_name', public: 'public', hasArchive: 'has_archive' };
    for (let [key, value] of Object.entries(dataNames)) {
      const input: HTMLInputElement = document.querySelector(`#${value}`);
      if(key === 'postType') input.disabled = true;
      if (key === 'public' || key === 'hasArchive') {
        input.checked = this.dataset[key] === '1';
      } else {
        input.value = this.dataset[key];
      }
    }

    // Render a Reset button for cpt-form
    const form: HTMLFormElement = document.querySelector('.cpt-form');
    const submitWrapper: HTMLParagraphElement = document.querySelector('.cpt-form .submit');
    let resetBtn: HTMLButtonElement | null = document.querySelector('button[type="reset"]');
    if(!resetBtn) {
      resetBtn = document.createElement('button');
      resetBtn.type = 'reset';
      resetBtn.textContent = 'Reset';
      resetBtn.classList.add('button');
      // Add event listener to reset button
      resetBtn.addEventListener('click', (event) => {
        event.preventDefault();
        modifyNav.textContent = "Create new custom post type";
        (document.querySelector(`#post_type`) as HTMLInputElement).disabled = false;
        form.reset();
      });
    }


    submitWrapper.append(resetBtn);
  },
};
