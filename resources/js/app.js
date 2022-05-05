require("./components/autoload");
require("../../vendor/marcusvbda/vstack/src/Assets/js/components/autoload");
require("./helpers");

VueApp.appendStoreModule("dashboard", require("./stores/modules/dashboard.module").default);
VueApp.appendStoreModule("lead", require("./stores/modules/lead.module").default);
VueApp.appendStoreModule("wpp", require("./stores/modules/wpp.module").default);

VueApp.start();
