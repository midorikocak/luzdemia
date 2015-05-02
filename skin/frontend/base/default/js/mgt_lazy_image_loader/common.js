jQuery(document).ready(function($) {
  jQuery('img.lazy').jail({
    event: 'load+scroll',
    placeholder : "/skin/frontend/default/default/images/mgt_lazy_image_loader/loader.gif",
  });
});