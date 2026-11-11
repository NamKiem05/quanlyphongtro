import "./auth/login.js";
import "./admin.js";
import "./room/room.js";
import "./tenants/form_create_edit.js";
import "./tenants/modal_delete.js";
import "./chatbot/chatbot.js";

// quản lý hợp đồng
import "./contracts/detail_contracts.js";
import "./contracts/edit_contracts.js";
import "./contracts/create_contracts.js";
import "./contracts/action_dropdow.js";
import "./contracts/status_contract.js";


import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
