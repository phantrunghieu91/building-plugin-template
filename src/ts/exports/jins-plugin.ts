import HandleTabs from '../components/tabs';
import UpdateImageButton from '../components/updateImageButton';
import { customPostType } from '../pages/cpt';

declare const wp: any;

document.addEventListener('DOMContentLoaded', function (domEvent) {
  const handleTabs = new HandleTabs();
  const updateImage = new UpdateImageButton(wp);
  customPostType.init();
});
