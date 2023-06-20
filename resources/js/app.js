require("./components/autoload");
require("../../vendor/marcusvbda/vstack/src/Assets/js/components/autoload");
require("./helpers");

VueApp.appendStoreModule("dashboard", require("./stores/modules/dashboard.module").default);
VueApp.appendStoreModule("funnel_settings", require("./stores/modules/funnel_settings.module").default);
VueApp.start();
