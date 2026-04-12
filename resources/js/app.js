import "./bootstrap";
import "../css/app.css";

import Alpine from "alpinejs";
import axios from "axios";

window.Alpine = Alpine;

/*
|--------------------------------------------------------------------------
| CSRF TOKEN GLOBAL (IMPORTANT ⭐)
|--------------------------------------------------------------------------
*/

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
}

Alpine.start();
