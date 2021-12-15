/*
const webhook = "_WEBHOOK_URL_";
const webhook_name = "_WEBHOOK_NAME_";

window.addEventListener("load", () => {
  console.log(`script direto para ${webhook_name} pronto!`);
  document.addEventListener("submit", (el) => {
    let elements = el.target.elements;
    let obj = {};
    for (let i = 0; i < elements.length; i++) {
      let item = elements.item(i);
      if (item.name) {
        if (item.name == "city_id") {
          let city_element = $("input[name=city_id]");
          if (city_element) {
            let city = city_element.select2("data");
            if (city) {
              obj["city"] = city.name;
              obj["state"] = city.state;
            } else {
              obj[item.name] = item.value;
            }
          } else {
            obj[item.name] = item.value;
          }
        } else {
          obj[item.name] = item.value;
        }
      }
    }
    obj["extra_source"] = "direct_script";
    $.post(webhook, obj);
  });
});
*/
