// ===============================
// 🔁 CONFIG
// ===============================
const USE_MOCK = true; // 🔌 BACKEND: mudar para false depois

// ===============================
// 🔥 MOCK
// ===============================
const mockData = {
    user: {
        name: "Nick",
        email: "nick@email.com",
        phone: "(11) 99999-9999"
    },
    appointments: [
        {
            service: "Corte de cabelo",
            date: "2026-04-25 14:00:00",
            status: "pendente"
        },
        {
            service: "Consulta",
            date: "2026-04-20 10:00:00",
            status: "concluido"
        }
    ]
};

// ===============================
// 🚀 INIT
// ===============================
window.onload = () => {
    loadDashboard();
};

// ===============================
// 📡 LOAD
// ===============================
function loadDashboard() {
    showLoading();

    if (USE_MOCK) {
        setTimeout(() => {
            renderAll(mockData);
        }, 800);
    } else {
        fetch('/api/dashboard.php') // 🔌 BACKEND
            .then(res => res.json())
            .then(data => renderAll(data))
            .catch(() => showError());
    }
}

// ===============================
// 🎯 RENDER GERAL
// ===============================
function renderAll(data) {
    renderUser(data.user);
    renderAppointments(data.appointments);
    hideLoading();
}

// ===============================
// 👤 USER
// ===============================
function renderUser(user) {
    document.getElementById("userNameNav").innerText = `Olá, ${user.name}`;
    document.getElementById("userName").innerText = user.name;
    document.getElementById("userEmail").innerText = user.email;
    document.getElementById("userPhone").innerText = user.phone;
}

// ===============================
// 📅 APPOINTMENTS
// ===============================
function renderAppointments(appointments) {
    const container = document.getElementById("appointmentsList");

    if (!appointments || appointments.length === 0) {
        showEmpty();
        return;
    }

    container.innerHTML = "";

    appointments.forEach(app => {

        const { text, className } = getStatus(app.status);

        container.innerHTML += `
            <div class="card mb-3 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>Serviço:</strong> ${app.service}<br>
                        <strong>Data:</strong> ${formatDate(app.date)}
                    </div>
                    <span class="badge ${className}">${text}</span>
                </div>
            </div>
        `;
    });
}

// ===============================
// 🧠 HELPERS
// ===============================
function getStatus(status) {
    switch (status) {
        case "pendente":
            return { text: "Pendente", className: "bg-warning" };
        case "concluido":
            return { text: "Concluído", className: "bg-success" };
        case "cancelado":
            return { text: "Cancelado", className: "bg-danger" };
        default:
            return { text: "Desconhecido", className: "bg-secondary" };
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('pt-BR');
}

// ===============================
// 📌 STATES
// ===============================
function showLoading() {
    document.getElementById("loadingState").classList.remove("d-none");
}

function hideLoading() {
    document.getElementById("loadingState").classList.add("d-none");
}

function showError() {
    document.getElementById("loadingState").classList.add("d-none");
    document.getElementById("errorState").classList.remove("d-none");
}

function showEmpty() {
    document.getElementById("loadingState").classList.add("d-none");
    document.getElementById("emptyState").classList.remove("d-none");
}