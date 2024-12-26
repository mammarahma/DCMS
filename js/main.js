// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", function () {
    // Form validation for registration
    const registrationForm = document.querySelector('form');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function (event) {
            const username = document.querySelector('input[name="username"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const passwordVerify = document.querySelector('input[name="password_verify"]').value;
            const patientSin = document.querySelector('input[name="patient_sin"]').value;
            const email = document.querySelector('input[name="email"]').value;

            let errors = [];

            // Validate username
            if (username.length < 3) {
                errors.push("Username must be at least 3 characters long.");
            }

            // Validate password
            if (password.length < 6) {
                errors.push("Password must be at least 6 characters long.");
            }

            // Validate password match
            if (password !== passwordVerify) {
                errors.push("Passwords do not match.");
            }

            // Validate SIN
            if (!/^\d{9}$/.test(patientSin)) {
                errors.push("SIN must be exactly 9 digits.");
            }

            // Validate email
            if (!validateEmail(email)) {
                errors.push("Please enter a valid email address.");
            }

            // If there are errors, prevent form submission and display errors
            if (errors.length > 0) {
                event.preventDefault();
                alert(errors.join("\n"));
            }
        });
    }

    // Function to validate email format
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    // Example of fetching appointments via AJAX
    const fetchAppointmentsButton = document.getElementById('fetchAppointments');
    if (fetchAppointmentsButton) {
        fetchAppointmentsButton.addEventListener('click', function () {
            fetchAppointments();
        });
    }

    function fetchAppointments() {
        fetch('path/to/your/api/appointments.php') // Adjust the path to your API endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                displayAppointments(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    function displayAppointments(appointments) {
        const appointmentsList = document.getElementById('appointmentsList');
        appointmentsList.innerHTML = ''; // Clear existing appointments

        if (appointments.length === 0) {
            appointmentsList.innerHTML = '<li>No appointments found.</li>';
            return;
        }

        appointments.forEach(appointment => {
            const li = document.createElement('li');
            li.innerHTML = `
                Appointment ID: ${appointment.appointment_id}<br>
                Patient ID: ${appointment.patient_id}<br>
                Date: ${appointment.date_of_appointment}<br>
                Time: ${appointment.start_time} - ${appointment.end_time}<br>
                Status: ${appointment.appointment_status}
            `;
            appointmentsList.appendChild(li);
        });
    }
});