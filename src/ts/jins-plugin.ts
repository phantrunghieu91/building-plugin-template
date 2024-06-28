import HandleTabs from "./components/tabs";
import { customPostType } from "./pages/cpt";

document.addEventListener('DOMContentLoaded', function(domEvent){
  const handleTabs = new HandleTabs();  
  customPostType.init();
});