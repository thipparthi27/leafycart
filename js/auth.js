function getLoggedInUser() {
    try {
        return JSON.parse(localStorage.getItem("loggedInUser"));
    } catch (error) {
        return null;
    }
}

function setLoggedInUser(user) {
    if (user && user.name) {
        localStorage.setItem("loggedInUser", JSON.stringify(user));
    }
}

function clearLoggedInUser() {
    localStorage.removeItem("loggedInUser");
}

function isLoggedIn() {
    return !!getLoggedInUser();
}

function requireLogin(message) {
    if (!isLoggedIn()) {
        alert(message || "Please login to continue.");
        window.location.href = "login.html";
        return false;
    }
    return true;
}

function setAuthNavItemVisibility(selector, isVisible) {
    document.querySelectorAll(selector).forEach(function (item) {
        item.hidden = !isVisible;
        item.setAttribute("aria-hidden", String(!isVisible));

        if (isVisible) {
            item.style.removeProperty("display");
        } else {
            item.style.display = "none";
        }
    });
}

function updateAuthUi() {
    if (window.updateAuthStateClass) {
        window.updateAuthStateClass();
    }

    const user = getLoggedInUser();
    const userGreeting = document.getElementById("user-greeting");
    const greetingText = document.getElementById("greeting-text");
    const hasLoggedInUser = !!(user && user.name);

    setAuthNavItemVisibility("#nav-login", !hasLoggedInUser);
    setAuthNavItemVisibility("#nav-profile", hasLoggedInUser);
    setAuthNavItemVisibility("#nav-logout", hasLoggedInUser);

    if (hasLoggedInUser) {
        if (userGreeting) {
            userGreeting.style.display = "block";
            greetingText.textContent = "👋 Hello, " + user.name;
        }
    } else {
        if (userGreeting) userGreeting.style.display = "none";
    }
}

function openProfile() {
    const user = getLoggedInUser();
    if (!user) return;
    
    document.getElementById("modal-user-name").textContent = user.name || "N/A";
    document.getElementById("modal-user-email").textContent = user.email || "N/A";
    document.getElementById("modal-user-address").textContent = user.address || "Not provided";
    
    let orderHistory = "No orders yet";
    if (user.orders && user.orders.length > 0) {
        orderHistory = user.orders.map(order => `Order #${order.id} - $${order.total}`).join("<br>");
    }
    document.getElementById("modal-order-history").innerHTML = orderHistory;
    document.getElementById("modal-tracking").textContent = user.tracking || "No active shipments";
    
    document.getElementById("profileModal").style.display = "block";
}

function closeProfile() {
    document.getElementById("profileModal").style.display = "none";
}

function logoutUser() {
    clearLoggedInUser();
    updateAuthUi();
    window.location.href = window.location.pathname.substring(window.location.pathname.lastIndexOf('/') + 1) || 'index.html';
}

document.addEventListener("DOMContentLoaded", function() {
    // Update auth UI
    updateAuthUi();
    
    // Profile button functionality
    const closeModal = document.getElementById("closeModal");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const modal = document.getElementById("profileModal");
    
    document.querySelectorAll("#profileBtn").forEach(function (profileBtn) {
        profileBtn.addEventListener("click", function(e) {
            e.preventDefault();
            openProfile();
        });
    });
    
    if (closeModal) {
        closeModal.addEventListener("click", closeProfile);
    }
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", closeProfile);
    }
    
    if (modal) {
        window.addEventListener("click", function(e) {
            if (e.target === modal) {
                closeProfile();
            }
        });
    }
    
    // Logout button functionality
    document.querySelectorAll("#logoutBtn").forEach(function (logoutBtn) {
        logoutBtn.addEventListener("click", function (event) {
            event.preventDefault();
            logoutUser();
        });
    });
});
