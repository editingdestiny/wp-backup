<?php
/**
 * Plugin Name:       Kids Activity Manager
 * Description:       A custom app to manage and display kids activities and bookings.
 * Version:           1.1
 * Author:            You & Your AI Assistant
 */

// Security: Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode to display the SUPPLIER DASHBOARD.
 * Usage: [supplier_dashboard]
 */
function kids_app_render_supplier_dashboard() {
    ob_start(); // Start output buffering to capture all HTML, CSS, and JS
    ?>
    
    <div id="kids-activity-app">
        <div id="dashboard-view">
            <h1>My Activities Dashboard</h1>
            <button id="show-form-to-add-btn" class="add-new-btn">Add New Activity</button>
            <div id="activityTableContainer" class="table-container">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Activity Name</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Cost (£)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activityList"></tbody>
                </table>
                <div id="loadingState">Loading activities...</div>
            </div>
            <hr class="section-divider">
            <h2>New Booking Requests</h2>
            <div id="bookingsTableContainer" class="table-container">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Activity Name</th>
                            <th>Booking Date</th>
                            <th>Parent Name</th>
                            <th>Parent Contact</th>
                            <th>Child Name & Age</th>
                            <th>Allergies</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingList"></tbody>
                </table>
                <div id="bookingsLoadingState">Loading booking requests...</div>
            </div>
        </div>
        <div id="form-view" style="display: none;">
            <h1 id="formTitle">Create a New Activity</h1>
            <div id="activityForm">
                <div class="form-group"><label for="activityName">Activity Name</label><input type="text" id="activityName" required></div>
                <div class="form-group"><label for="description">Description</label><textarea id="description" rows="4" required></textarea></div>
                <div class="form-group"><label for="category">Category</label><select id="category" required><option value="" disabled selected>Select...</option><option>Sports</option><option>Arts & Crafts</option><option>Music</option><option>Educational</option><option>Outdoor</option></select></div>
                <div class="form-group"><label for="location">Location</label><input type="text" id="location" required></div>
                <div class="form-group"><label for="dayOfWeek">Day</label><select id="dayOfWeek" required><option value="" disabled selected>Select...</option><option>Monday</option><option>Tuesday</option><option>Wednesday</option><option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option></select></div>
                <div class="form-group"><label for="startTime">Start Time</label><input type="time" id="startTime" required></div>
                <div class="form-group"><label for="endTime">End Time</label><input type="time" id="endTime" required></div>
                <div class="form-group"><label for="ageGroup">Age Group</label><input type="text" id="ageGroup" placeholder="e.g., 5-7 years" required></div>
                <div class="form-group"><label for="cost">Cost (£)</label><input type="number" id="cost" min="0" step="0.01" required></div>
                <div class="form-group"><label for="maxParticipants">Max Participants</label><input type="number" id="maxParticipants" min="1" required></div>
                <button type="button" id="submitBtn" class="submit-btn">Create Activity</button>
                <button type="button" id="cancel-btn" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <style>
        #kids-activity-app { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #ffffff; padding: 20px; border-radius: 8px; }
        #kids-activity-app h1 { color: #333; margin-top: 0; }
        #kids-activity-app h2 { color: #333; margin-top: 40px; border-bottom: 2px solid #007bff; padding-bottom: 10px;}
        .table-container { overflow-x: auto; }
        #kids-activity-app .responsive-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        #kids-activity-app .responsive-table th, #kids-activity-app .responsive-table td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; white-space: nowrap; }
        #kids-activity-app .responsive-table th { background-color: #007bff; color: white; }
        #kids-activity-app .responsive-table td { color: #333; }
        #kids-activity-app .responsive-table tr:nth-child(even) { background-color: #f2f2f2; }
        #kids-activity-app .actions button { padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; margin-right: 5px; }
        #kids-activity-app .edit-btn { background-color: #ffc107; color: black; }
        #kids-activity-app .delete-btn { background-color: #dc3545; color: white; }
        #kids-activity-app #loadingState, #kids-activity-app #bookingsLoadingState { text-align: center; padding: 20px; color: #555; }
        #kids-activity-app .add-new-btn, #kids-activity-app .cancel-btn, #kids-activity-app .submit-btn { color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-bottom: 10px; }
        #kids-activity-app .add-new-btn { background-color: #28a745; }
        #kids-activity-app .submit-btn { background-color: #007bff; width: 100%; margin-top: 10px; }
        #kids-activity-app .cancel-btn { background-color: #6c757d; width: 100%; margin-top: 10px;}
        #kids-activity-app .form-group { margin-bottom: 15px; }
        #kids-activity-app label { display: block; margin-bottom: 5px; font-weight: 600; color: #111; }
        #kids-activity-app input, #kids-activity-app textarea, #kids-activity-app select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .section-divider { border: 0; height: 1px; background: #ddd; margin: 40px 0; }
        .confirm-booking-btn { background-color: #28a745; color: white; }
        .decline-booking-btn { background-color: #dc3545; color: white; }
        .status-text { padding: 4px 8px; border-radius: 4px; color: white; font-weight: bold; font-size: 0.9em; }
        .status-pending { background-color: #ffc107; color: black; }
        .status-confirmed { background-color: #28a745; }
        .status-declined { background-color: #6c757d; }
        @media (max-width: 768px) {
            #kids-activity-app .responsive-table td { white-space: normal; }
            #kids-activity-app .responsive-table thead { display: none; }
            #kids-activity-app .responsive-table, #kids-activity-app .responsive-table tbody, #kids-activity-app .responsive-table tr, #kids-activity-app .responsive-table td { display: block; width: 100%; }
            #kids-activity-app .responsive-table tr { border: 1px solid #ddd; border-radius: 5px; margin-bottom: 15px; padding: 10px; }
            #kids-activity-app .responsive-table td { display: flex; justify-content: space-between; align-items: center; padding: 10px 5px; border: none; border-bottom: 1px solid #eee; }
            #kids-activity-app .responsive-table td:last-child { border-bottom: none; }
            #kids-activity-app .responsive-table td::before { content: attr(data-label); font-weight: bold; margin-right: 10px; color: #333; white-space: nowrap; }
        }
    </style>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import { getFirestore, collection, getDocs, doc, deleteDoc, addDoc, setDoc, getDoc, query, orderBy, updateDoc } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";
        const firebaseConfig = { apiKey: "AIzaSyDcD9x3v481QuSZ-ZJoLS1zkpFARZvrjok", authDomain: "kids-activities-809f5.firebaseapp.com", projectId: "kids-activities-809f5", storageBucket: "kids-activities-809f5.appspot.com", messagingSenderId: "1081258390333", appId: "1:1081258390333:web:16264fa8dd85207d160b54" };
        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);
        const dashboardView = document.getElementById('dashboard-view');
        const formView = document.getElementById('form-view');
        const activityListBody = document.getElementById('activityList');
        const loadingState = document.getElementById('loadingState');
        const formDiv = document.getElementById('activityForm');
        const formTitle = document.getElementById('formTitle');
        const submitBtn = document.getElementById('submitBtn');
        let currentActivityId = null; 
        const bookingListBody = document.getElementById('bookingList');
        const bookingsLoadingState = document.getElementById('bookingsLoadingState');
        function showView(viewName) {
            dashboardView.style.display = 'none';
            formView.style.display = 'none';
            document.getElementById(viewName).style.display = 'block';
        }
        async function loadActivities() {
            activityListBody.innerHTML = '';
            loadingState.style.display = 'block';
            try {
                const querySnapshot = await getDocs(collection(db, "activities"));
                loadingState.style.display = 'none';
                if(querySnapshot.empty) { loadingState.style.display = 'block'; loadingState.textContent = 'No activities found.'; }
                querySnapshot.forEach(doc => {
                    const activity = doc.data();
                    const activityId = doc.id;
                    const row = document.createElement('tr');
                    row.innerHTML = `<td data-label="Activity Name">${activity.activityName}</td><td data-label="Day">${activity.dayOfWeek}</td><td data-label="Time">${activity.startTime} - ${activity.endTime}</td><td data-label="Location">${activity.location}</td><td data-label="Cost (£)">${activity.cost.toFixed(2)}</td><td data-label="Actions" class="actions"><button type="button" class="edit-btn" data-id="${activityId}">Edit</button><button type="button" class="delete-btn" data-id="${activityId}">Delete</button></td>`;
                    activityListBody.appendChild(row);
                });
            } catch (error) { console.error("Error loading activities: ", error); loadingState.textContent = 'Failed to load activities.'; }
        }
        async function loadBookings() {
            bookingListBody.innerHTML = '';
            bookingsLoadingState.style.display = 'block';
            try {
                const bookingsQuery = query(collection(db, "bookings"), orderBy("bookingDate", "desc"));
                const querySnapshot = await getDocs(bookingsQuery);
                bookingsLoadingState.style.display = 'none';
                if (querySnapshot.empty) { bookingsLoadingState.style.display = 'block'; bookingsLoadingState.textContent = 'No new booking requests.'; }
                querySnapshot.forEach(doc => {
                    const booking = doc.data();
                    const row = document.createElement('tr');
                    const bookingDate = booking.bookingDate.toDate().toLocaleDateString('en-GB');
                    const statusClass = `status-${booking.bookingStatus.toLowerCase()}`;
                    row.innerHTML = `
                        <td data-label="Activity">${booking.activityName}</td><td data-label="Booking Date">${bookingDate}</td>
                        <td data-label="Parent Name">${booking.parentName}</td><td data-label="Parent Contact">${booking.parentEmail}<br>${booking.parentPhone}</td>
                        <td data-label="Child">${booking.childName} (Age: ${booking.childAge})</td><td data-label="Allergies">${booking.hasAllergies === 'Yes' ? booking.allergyDetails : 'None'}</td>
                        <td data-label="Status"><span class="status-text ${statusClass}">${booking.bookingStatus}</span></td>
                        <td data-label="Actions" class="actions"><button type="button" class="confirm-booking-btn" data-id="${doc.id}">Confirm</button><button type="button" class="decline-booking-btn" data-id="${doc.id}">Decline</button></td>`;
                    bookingListBody.appendChild(row);
                });
            } catch (error) { console.error("Error loading bookings: ", error); bookingsLoadingState.textContent = 'Failed to load bookings.'; }
        }
        if (document.body.contains(dashboardView)) {
            showView('dashboard-view');
            loadActivities();
            loadBookings();
            dashboardView.addEventListener('click', async (e) => {
                const target = e.target;
                if (target.id === 'show-form-to-add-btn') {
                    currentActivityId = null; 
                    formDiv.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                    formTitle.textContent = 'Create a New Activity';
                    submitBtn.textContent = 'Create Activity';
                    showView('form-view');
                } else if (target.classList.contains('edit-btn')) {
                    currentActivityId = target.dataset.id;
                    const docRef = doc(db, "activities", currentActivityId);
                    const docSnap = await getDoc(docRef);
                    if (docSnap.exists()) {
                        const data = docSnap.data();
                        document.getElementById('activityName').value = data.activityName; document.getElementById('description').value = data.description; document.getElementById('category').value = data.category; document.getElementById('location').value = data.location; document.getElementById('dayOfWeek').value = data.dayOfWeek; document.getElementById('startTime').value = data.startTime; document.getElementById('endTime').value = data.endTime; document.getElementById('ageGroup').value = data.ageGroup; document.getElementById('cost').value = data.cost; document.getElementById('maxParticipants').value = data.maxParticipants;
                    }
                    formTitle.textContent = 'Update Activity';
                    submitBtn.textContent = 'Save Changes';
                    showView('form-view');
                } else if (target.classList.contains('delete-btn')) {
                    try { await deleteDoc(doc(db, "activities", target.dataset.id)); loadActivities(); } catch (error) { console.error("Error deleting document: ", error); alert("Delete failed."); }
                }
                else if (target.classList.contains('confirm-booking-btn')) {
                    try { await updateDoc(doc(db, "bookings", target.dataset.id), { bookingStatus: "Confirmed" }); loadBookings(); } catch (error) { console.error("Error confirming booking: ", error); alert("Failed to confirm booking."); }
                } else if (target.classList.contains('decline-booking-btn')) {
                    try { await updateDoc(doc(db, "bookings", target.dataset.id), { bookingStatus: "Declined" }); loadBookings(); } catch (error) { console.error("Error declining booking: ", error); alert("Failed to decline booking."); }
                }
            });
            submitBtn.addEventListener('click', async (e) => {
                e.preventDefault(); 
                const formData = { activityName: document.getElementById('activityName').value, description: document.getElementById('description').value, category: document.getElementById('category').value, location: document.getElementById('location').value, dayOfWeek: document.getElementById('dayOfWeek').value, startTime: document.getElementById('startTime').value, endTime: document.getElementById('endTime').value, ageGroup: document.getElementById('ageGroup').value, cost: Number(document.getElementById('cost').value), maxParticipants: Number(document.getElementById('maxParticipants').value) };
                try {
                    if (currentActivityId) { await setDoc(doc(db, "activities", currentActivityId), formData, { merge: true }); } else { await addDoc(collection(db, "activities"), formData); }
                    loadActivities();
                    loadBookings();
                    showView('dashboard-view');
                } catch (error) { console.error("Error saving document: ", error); alert("Save failed."); }
            });
            document.getElementById('cancel-btn').addEventListener('click', () => { showView('dashboard-view'); });
        }
    </script>
    
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('supplier_dashboard', 'kids_app_render_supplier_dashboard');


/**
 * Shortcode to display the CONSUMER VIEW.
 * Usage: [consumer_view]
 */
function kids_app_render_consumer_view() {
    ob_start();
    ?>

    <div id="consumer-activity-view">
        <h1>Find an Activity</h1>
        <p>Browse our list of exciting activities for kids in and around Bexleyheath.</p>
        <div id="activity-card-grid"></div>
        <div id="consumer-loading-state" style="text-align: center; padding: 30px; font-size: 18px;">Loading activities...</div>
        <div id="booking-modal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <button id="close-modal-btn" class="modal-close-btn">&times;</button>
                <div id="booking-form-view">
                    <h2 id="booking-form-title">Book Your Spot</h2>
                    <div id="booking-form-fields">
                        <div class="form-group"><label for="parentName">Parent's Full Name</label><input type="text" id="parentName" required></div>
                        <div class="form-group"><label for="parentEmail">Parent's Email</label><input type="email" id="parentEmail" required></div>
                        <div class="form-group"><label for="parentPhone">Parent's Phone</label><input type="tel" id="parentPhone" required></div>
                        <div class="form-group"><label for="childName">Child's Full Name</label><input type="text" id="childName" required></div>
                        <div class="form-group"><label for="childAge">Child's Age</label><input type="number" id="childAge" required></div>
                        <div class="form-group">
                            <label>Do you need to declare any allergies?</label>
                            <label class="radio-label"><input type="radio" name="allergyChoice" value="No" required> No</label>
                            <label class="radio-label"><input type="radio" name="allergyChoice" value="Yes"> Yes</label>
                        </div>
                        <div id="allergy-details-container" class="form-group" style="display: none;"><label for="allergyDetails">Please provide details of the allergies</label><textarea id="allergyDetails" rows="3"></textarea></div>
                        <div class="form-group waiver"><input type="checkbox" id="infoWaiver" required><label for="infoWaiver">I agree to the information security waiver and give consent to be contacted regarding this booking.</label></div>
                        <button id="submit-booking-btn" class="submit-booking-btn">Submit Booking Request</button>
                    </div>
                </div>
                <div id="booking-thank-you-view" style="display: none; text-align: center; padding: 40px 20px;">
                    <h2>Thank You!</h2>
                    <p>Your booking request has been sent to the provider. They will contact you shortly to confirm your spot and arrange payment.</p>
                    <button id="close-thank-you-btn" class="submit-booking-btn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #consumer-activity-view { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; padding: 10px; }
        #consumer-activity-view h1 { color: #333; }
        #activity-card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .activity-card { border: 1px solid #e0e0e0; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; }
        .activity-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.1); }
        .card-header { background-color: #007bff; color: white; padding: 15px; }
        .card-header h3 { margin: 0; font-size: 1.25em; }
        .card-category { font-size: 0.9em; opacity: 0.9; margin-top: 5px; }
        .card-body { padding: 15px; flex-grow: 1; }
        .card-body p { margin: 0 0 15px 0; color: #555; line-height: 1.5; }
        .card-details { display: flex; flex-direction: column; gap: 10px; }
        .card-details span { display: flex; align-items: center; gap: 8px; color: #333; }
        .card-details svg { width: 18px; height: 18px; fill: #555; }
        .card-footer { background-color: #f8f9fa; padding: 15px; margin-top: auto; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
        .card-cost { font-size: 1.4em; font-weight: bold; color: #28a745; }
        .card-book-btn { background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; cursor: pointer; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; display: flex; justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; position: relative; }
        .modal-close-btn { position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #888; }
        .modal-content .form-group { margin-bottom: 15px; }
        .modal-content label { display: block; margin-bottom: 5px; font-weight: 600; color: #111; }
        .modal-content .radio-label { display: inline-block; margin-right: 20px; font-weight: normal;}
        .modal-content .waiver { display: flex; align-items: flex-start; gap: 10px; }
        .modal-content .waiver input { flex-shrink: 0; margin-top: 5px; }
        .modal-content .waiver label { font-weight: normal; }
        .submit-booking-btn { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 5px; width: 100%; font-size: 16px; cursor: pointer; }
    </style>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import { getFirestore, collection, getDocs, query, addDoc } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";
        const firebaseConfig = { apiKey: "AIzaSyDcD9x3v481QuSZ-ZJoLS1zkpFARZvrjok", authDomain: "kids-activities-809f5.firebaseapp.com", projectId: "kids-activities-809f5", storageBucket: "kids-activities-809f5.appspot.com", messagingSenderId: "1081258390333", appId: "1:1081258390333:web:16264fa8dd85207d160b54" };
        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);
        const grid = document.getElementById('activity-card-grid');
        const loadingState = document.getElementById('consumer-loading-state');
        const bookingModal = document.getElementById('booking-modal');
        const bookingFormView = document.getElementById('booking-form-view');
        const bookingThankYouView = document.getElementById('booking-thank-you-view');
        const bookingFormFields = document.getElementById('booking-form-fields');
        let currentBookingActivityId = null; 
        const calendarIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H64V24c0-13.3-10.7-24-24-24S16 10.7 16 24V64H0V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V64H352V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H152V24zM384 112H64V448H384V112z"/></svg>`;
        const clockIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>`;
        const locationIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>`;
        const ageIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>`;
        async function loadConsumerActivities() {
            if (!grid) return;
            grid.innerHTML = '';
            loadingState.style.display = 'block';
            try {
                const activitiesQuery = query(collection(db, "activities"));
                const querySnapshot = await getDocs(activitiesQuery);
                if (querySnapshot.empty) { loadingState.textContent = 'No activities are available right now.'; } else { loadingState.style.display = 'none'; }
                querySnapshot.forEach(doc => {
                    const activity = doc.data();
                    const card = document.createElement('div');
                    card.className = 'activity-card';
                    card.innerHTML = `<div class="card-header"><h3>${activity.activityName}</h3><div class="card-category">${activity.category}</div></div><div class="card-body"><p>${activity.description}</p><div class="card-details"><span>${calendarIcon} ${activity.dayOfWeek}</span><span>${clockIcon} ${activity.startTime} - ${activity.endTime}</span><span>${locationIcon} ${activity.location}</span><span>${ageIcon} Suitable for ages: ${activity.ageGroup}</span></div></div><div class="card-footer"><span class="card-cost">£${activity.cost.toFixed(2)}</span><a href="#" class="card-book-btn" data-id="${doc.id}" data-name="${activity.activityName}">Book Now</a></div>`;
                    grid.appendChild(card);
                });
            } catch (error) { console.error("Error loading activities: ", error); loadingState.textContent = 'Could not load activities.'; }
        }
        if (document.body.contains(grid)) {
            grid.addEventListener('click', (e) => {
                if (e.target.classList.contains('card-book-btn')) {
                    e.preventDefault();
                    currentBookingActivityId = e.target.dataset.id;
                    document.getElementById('booking-form-title').textContent = `Book: ${e.target.dataset.name}`;
                    bookingFormView.style.display = 'block';
                    bookingThankYouView.style.display = 'none';
                    bookingFormFields.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="number"], textarea').forEach(input => input.value = '');
                    bookingFormFields.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => input.checked = false);
                    document.getElementById('allergy-details-container').style.display = 'none';
                    bookingModal.style.display = 'flex';
                }
            });
            function closeModal() { bookingModal.style.display = 'none'; }
            document.getElementById('close-modal-btn').addEventListener('click', closeModal);
            document.getElementById('close-thank-you-btn').addEventListener('click', closeModal);
            bookingModal.addEventListener('change', (e) => {
                if (e.target.name === 'allergyChoice') {
                    document.getElementById('allergy-details-container').style.display = (e.target.value === 'Yes') ? 'block' : 'none';
                }
            });
            document.getElementById('submit-booking-btn').addEventListener('click', async () => {
                if (!document.getElementById('parentName').value || !document.getElementById('parentEmail').value || !document.getElementById('childName').value || !document.getElementById('infoWaiver').checked) {
                    alert('Please fill out all required fields and agree to the waiver.');
                    return;
                }
                const bookingData = { activityId: currentBookingActivityId, activityName: document.getElementById('booking-form-title').textContent.replace('Book: ', ''), parentName: document.getElementById('parentName').value, parentEmail: document.getElementById('parentEmail').value, parentPhone: document.getElementById('parentPhone').value, childName: document.getElementById('childName').value, childAge: document.getElementById('childAge').value, hasAllergies: document.querySelector('input[name="allergyChoice"]:checked').value, allergyDetails: document.getElementById('allergyDetails').value, waiverAgreed: document.getElementById('infoWaiver').checked, bookingStatus: 'Pending', bookingDate: new Date() };
                try {
                    await addDoc(collection(db, "bookings"), bookingData);
                    bookingFormView.style.display = 'none';
                    bookingThankYouView.style.display = 'block';
                } catch (error) { console.error("Error saving booking: ", error); alert("Sorry, there was an error submitting your booking. Please try again."); }
            });
            loadConsumerActivities();
        }
    </script>
    
    <?php
    return ob_get_clean();
}
add_shortcode('consumer_view', 'kids_app_render_consumer_view');

