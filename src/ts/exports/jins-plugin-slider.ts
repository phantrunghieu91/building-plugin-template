document.addEventListener('DOMContentLoaded', function (domEvent) {
  (function () {
    const slider = document.querySelector('.jins-plugin-testimonial-slider') as HTMLDivElement;
    const sliderWrapper = slider.querySelector('.jins-plugin-testimonial-slider__slider-wrapper') as HTMLDivElement;
    const sliderItems = slider.querySelectorAll('.jins-plugin-testimonial-slider__testimonial') as NodeListOf<HTMLDivElement>;
    const sliderButtonPrev = slider.querySelector('.jins-plugin-testimonial-slider__button-prev') as HTMLAnchorElement;
    const sliderButtonNext = slider.querySelector('.jins-plugin-testimonial-slider__button-next') as HTMLAnchorElement;
    return {
      // set slide item width equals to the first item width plus the margin-right of the first item
      sliderItemWidth: sliderItems[0].offsetWidth + parseInt(window.getComputedStyle(sliderItems[0]).marginRight),
      init: function () {
        sliderItems.forEach((item, index) => {
          if(index === 0) item.classList.add('jins-plugin-testimonial-slider__testimonial--active');
          item.dataset.index = index.toString();
        });
        this.disableButton(sliderButtonPrev, 'prev');
        sliderButtonPrev.addEventListener('click', () => this.moveSlider('prev'));
        sliderButtonNext.addEventListener('click', () => this.moveSlider('next'));
      },
      disableButton: function (button: HTMLAnchorElement, type: string, disable: boolean = true) {
        if(disable) {
          button.classList.add(`jins-plugin-testimonial-slider__button-${type}--disabled`);
        } else {
          button.classList.remove(`jins-plugin-testimonial-slider__button-${type}--disabled`);
        }
      },
      moveSlider: function (direction: string) {
        const activeItem = slider.querySelector('.jins-plugin-testimonial-slider__testimonial--active') as HTMLDivElement;
        const nextItem = direction === 'next' ? activeItem.nextElementSibling as HTMLDivElement : activeItem.previousElementSibling as HTMLDivElement;
        if(nextItem) {
          sliderWrapper.style.setProperty('--slider-translate-x', `-${this.sliderItemWidth * +nextItem.dataset.index}px`);
          activeItem.classList.remove('jins-plugin-testimonial-slider__testimonial--active');
          nextItem.classList.add('jins-plugin-testimonial-slider__testimonial--active');
          if(direction === 'next') {
            this.disableButton(sliderButtonPrev, 'prev', false);
          } else {
            this.disableButton(sliderButtonNext, 'next', false);
          }
          // check if nextItem is the first item or the last item
          if(nextItem.previousElementSibling === null) {
            this.disableButton(sliderButtonPrev, 'prev');
          } else if(nextItem.nextElementSibling === null) {
            this.disableButton(sliderButtonNext, 'next');
          }
        }
      }
    };
  })().init();
});
