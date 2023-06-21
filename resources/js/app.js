require("./components/autoload");
require("../../vendor/marcusvbda/vstack/src/Assets/js/components/autoload");
require("./helpers");

VueApp.appendStoreModule("dashboard", require("./stores/modules/dashboard.module").default);
VueApp.appendStoreModule("campaign", require("./stores/modules/campaign.module").default);
VueApp.start();
