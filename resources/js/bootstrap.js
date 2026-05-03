import axios from "axios";
import alpine from "alpinejs";
window.axios = axios;
window.Alpine = alpine;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
alpine.start();
