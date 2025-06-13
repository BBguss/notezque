document.addEventListener('DOMContentLoaded', function() {
    const reminderEnabled = document.getElementById("reminder_enabled");
    const reminderTemplate = document.getElementById("reminder_template");
    const reminderOptions = document.getElementById("reminder_options");
    const customReminder = document.getElementById("custom_reminder");

    // Toggle reminder options ketika checkbox dicentang/tidak dicentang
    if (reminderEnabled && reminderOptions) {
        reminderEnabled.addEventListener("change", function() {
            if (this.checked) {
                reminderOptions.classList.add("active");
            } else {
                reminderOptions.classList.remove("active");
                // Reset dropdown dan custom reminder
                if (reminderTemplate) reminderTemplate.value = "";
                if (customReminder) customReminder.classList.remove("active");
            }
        });
    }

    // Toggle custom reminder ketika dropdown berubah
    if (reminderTemplate && customReminder) {
        reminderTemplate.addEventListener("change", function () {
            const isCustom = this.value === "custom";
            customReminder.classList.toggle("active", isCustom);
        });
    }
});