/**
 * Represents a class that handles tabs functionality.
 */
export default class HandleTabs {
  protected navTab: HTMLElement | null;
  protected tabNavItems: NodeListOf<HTMLAnchorElement> | null;
  protected tabContent: HTMLDivElement | null;
  protected resizeObserver: ResizeObserver;

  /**
   * Constructs a new instance of the HandleTabs class.
   */
  constructor() {
    this.tabNavItems = document.querySelectorAll(`.nav__item`);
    this.tabContent = document.querySelector('.tab-content');
    this.navTab = document.querySelector('section.tabs');
    this.observerTabPaneWidth();
    this.handleChangeTab();
  }

  /**
   * Observes the width of the tab pane and updates the CSS variable '--pane-width' accordingly.
   */
  protected observerTabPaneWidth() {
    const resizeObserver: ResizeObserver = new ResizeObserver(entries => {
      for (let entry of entries) {
        const widthOfSection = entry.contentRect.width;
        this.tabContent?.style.setProperty('--pane-width', `${widthOfSection}px`);
      }
    });
    if (this.navTab){
      resizeObserver.observe(this.navTab);
    } 
  }

  /**
   * Handles the tab change event.
   */
  protected handleChangeTab() {
    this.tabNavItems.forEach((tabNavItem, idx) => {
      tabNavItem.addEventListener('click', clickEvent => {
        clickEvent.preventDefault();
        document.querySelector('.nav__item--active')?.classList.remove('nav__item--active');
        tabNavItem.classList.add('nav__item--active');
        // const targetPane = tabNavItem.dataset.target;
        // document.querySelector('.tab-pane--active')?.classList.remove('tab-pane--active');
        // document.querySelector(`${targetPane}`)?.classList.add('tab-pane--active');
        const translatePercent = 1 / this.tabNavItems.length;
        this.tabContent?.style.setProperty('--tab-translate-x', `-${idx * translatePercent * 100}%`);
      });
    });
  }

  /**
   * Gets the tab navigation items.
   * @returns The tab navigation items.
   */
  public get getTabNavItems(): NodeListOf<HTMLAnchorElement> | null{
    return this.tabNavItems;
  }

  /**
   * Gets the tab content.
   * @returns The tab content.
   */
  public get getTabContent(): HTMLDivElement | null{
    return this.tabContent;
  }

  /**
   * Gets the navigation tab.
   * @returns The navigation tab.
   */
  public get getNavTab(): HTMLElement | null{
    return this.navTab;
  }
}
