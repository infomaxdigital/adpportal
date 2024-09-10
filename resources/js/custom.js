import $ from 'jquery';

// Ensure jQuery is available globally
window.$ = window.jQuery = $;

// Check if the element with id 'selecteddays' exists before attaching the event listener
var selectedDaysElement = document.getElementById('selecteddays');
if (selectedDaysElement) {
    selectedDaysElement.addEventListener('change', function () {
        console.log('button clicked');
        document.getElementById('daySelectionForm').submit();
    });
}

// Check if the element with id 'clear-selection' exists before attaching the event listener
var clearSelectionElement = document.getElementById('clear-selection');
if (clearSelectionElement) {
    clearSelectionElement.addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('dance_style').selectedIndex = 0;
        document.getElementById('dance_level').selectedIndex = 0;
    });
}

$(function () {

    function filterBlocks() {
        var selectedDanceStyle = $('#dance_style').val();
        var selectedDanceLevel = $('#dance_level').val();

        console.log("Selected Dance Style:", selectedDanceStyle);
        console.log("Selected Dance Level:", selectedDanceLevel);
        if (typeof bookedClasses !== 'undefined') {
            // Create an object to store the count of bookings per class
            var classBookingsCount = {};

            // Count the number of bookings per classId
            $.each(bookedClasses, function (index, booking) {
                if (classBookingsCount[booking.classId]) {
                    classBookingsCount[booking.classId]++;
                } else {
                    classBookingsCount[booking.classId] = 1;
                }
            });

            $('.filter-block').each(function () {
                var $block = $(this);
                var matchFound = false;
                var isBooked = false;
                var endDate = null;
                var capacity = null;
                var classId = $block.data('class-id');
                var currentBookings = classBookingsCount[classId] || 0;

                // Check if the class has been booked
                $.each(bookedClasses, function (index, booking) {
                    if (booking.classId == classId) {
                        isBooked = true;
                        endDate = new Date(booking.endDate);
                        capacity = booking.capacity; // Get the capacity for the class
                        return false; // Break out of the loop
                    }
                });

                // Hide blocks based on booking status, capacity, and end date
                if ((isBooked && endDate) || (capacity && currentBookings >= capacity)) {
                    if (isBooked && endDate) {
                        var currentDate = new Date();
                        var diffTime = currentDate - endDate;
                        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        // Hide the block if the class was booked within the last 10 days
                        if (diffDays <= 10) {
                            console.log("Hiding block due to recent booking within 10 days:", classId);
                            $block.hide();
                            return; // Skip the rest of the logic for this block
                        }
                    }
                    // Hide the block if capacity is reached
                    if (capacity && currentBookings >= capacity) {
                        console.log("Hiding block due to capacity reached:", classId);
                        $block.hide();
                        return; // Skip the rest of the logic for this block
                    }
                }

                $block.find('.filter-content').each(function () {
                    var blockStyles = $(this).data('styles').toLowerCase().split(', ');  // Convert styles to lowercase
                    var blockLevel = $(this).data('level').toString();

                    console.log("Block Styles:", blockStyles);
                    console.log("Block Level:", blockLevel);

                    var matchesStyle = selectedDanceStyle ? blockStyles.includes($('#dance_style option:selected').text().toLowerCase()) : true;
                    var matchesLevel = selectedDanceLevel ? blockLevel === selectedDanceLevel : true;

                    console.log("Matches Style:", matchesStyle);
                    console.log("Matches Level:", matchesLevel);

                    if (matchesStyle && matchesLevel) {
                        matchFound = true;
                        return false; // Exit the loop early if a match is found
                    }
                });

                if (matchFound) {
                    console.log("Showing block for teacher:", $block.data('teacher'));
                    $block.show();
                } else {
                    console.log("Hiding block for teacher:", $block.data('teacher'));
                    $block.hide();
                }
            });
        } else {
            $('.filter-block').each(function () {
                var $block = $(this);
                var matchFound = false;

                $block.find('.filter-content').each(function () {
                    var blockStyles = $(this).data('styles').toLowerCase().split(', ');  // Convert styles to lowercase
                    var blockLevel = $(this).data('level').toString();

                    console.log("Block Styles:", blockStyles);
                    console.log("Block Level:", blockLevel);

                    var matchesStyle = selectedDanceStyle ? blockStyles.includes($('#dance_style option:selected').text().toLowerCase()) : true;
                    var matchesLevel = selectedDanceLevel ? blockLevel === selectedDanceLevel : true;

                    console.log("Matches Style:", matchesStyle);
                    console.log("Matches Level:", matchesLevel);

                    if (matchesStyle && matchesLevel) {
                        matchFound = true;
                        return false; // Exit the loop early if a match is found
                    }
                });

                if (matchFound) {
                    console.log("Showing block for teacher:", $block.data('teacher'));
                    $block.show();
                } else {
                    console.log("Hiding block for teacher:", $block.data('teacher'));
                    $block.hide();
                }
            });
        }
    }

    $('#dance_style, #dance_level').on('change', filterBlocks);

    $('#clear-selection').on('click', function (e) {
        e.preventDefault();
        $('#dance_style').val('');
        $('#dance_level').val('');
        filterBlocks();
    });

    // Run filterBlocks initially in case dropdowns have default values
    filterBlocks();
});


$(function () {
    // Handle click event on booking button
    $('.booking-btn').on('click', function (e) {
        e.preventDefault();

        // Hide Step 1
        document.getElementById('step1').style.display = 'none';
        // Show Step 2
        document.getElementById('step2').style.display = 'block';
        setActiveStep(2);
        // Get the parent .filter-block of the clicked button
        var parentBlock = $(this).closest('.filter-block');

        // Retrieve the teacher ID from the button
        var teacherId = $(this).data('bookingbtn-id');

        // Update the content of #data1 div
        var teacherName = parentBlock.data('teachername');
        var days = parentBlock.data('days');
        var styles = parentBlock.find('.filter-content').map(function () {
            return $(this).text();
        }).get().join('<br>');

        $('#data1').html('<strong>Teacher Name:</strong> ' + teacherName + '<br><strong>Days:</strong> ' + days + '<br><strong>Styles:</strong><br>' + styles + '<div><a href="#" class="btn btn-primary backbtn1" >Back</a></div>');

        // Fetch class data using AJAX
        $.ajax({
            url: '/get-classes/' + teacherId,
            method: 'GET',
            success: function (response) {
                var groupedData = {};
                var bookedClasses = response.bookedClasses;

                // console.log(bookedClasses);

                // Group classes by day
                $.each(response.classes, function (index, classInfo) {
                    if (!groupedData[classInfo.days]) {
                        groupedData[classInfo.days] = [];
                    }
                    groupedData[classInfo.days].push(classInfo);
                });

                var classData = '';

                $.each(groupedData, function (day, classes) {

                    $.each(classes, function (index, classInfo) {

                        var bookingInfo = bookedClasses.find(function (bookedClass) {
                            return bookedClass.classId === classInfo.id;
                        });

                        // console.log(bookingInfo);

                        var isBooked = bookingInfo !== undefined;
                        var buttonClass = 'btn-primary';
                        var buttonText = 'Book Now';
                        var buttonDisabled = '';

                        if (isBooked) {
                            // debugger;
                            var endDate = new Date(bookingInfo.endDate);
                            var currentDate = new Date();
                            var diffTime = currentDate - endDate;
                            var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                            // Disable the button if the current date is within 10 days after the endDate
                            if (diffDays <= 10) {
                                // buttonClass = 'btn-secondary';
                                // buttonText = 'Unavailable';
                                // buttonDisabled = 'disabled-link';
                                return;
                            }
                        }
                        classData += '<strong>Days:</strong> ' + day + '<br>';
                        $("#classType").val(classInfo.classType);
                        classData += '<div class="my-3"><strong>Time Slot:</strong> ' + classInfo.startTime + '-' + classInfo.endTime +
                            '<a href="#" class="btn ' + buttonClass + ' ' + buttonDisabled + ' book-now" data-class-id="' + classInfo.id + '" data-teacher-id="' + classInfo.teacherId + '">' + buttonText + '</a></div>';
                    });
                    //classData += '<hr>'; // Optional separator for each day
                });


                // Update the content of #data2 div
                $('#data2').html(classData);
            },
            error: function () {
                $('#data2').html('An error occurred while fetching class data.');
            }
        });
    });

    // Handle click event on Book Now button inside class listings
    $(document).on('click', '.book-now', function (e) {
        e.preventDefault();

        // Hide Step 2
        document.getElementById('step2').style.display = 'none';
        // Show Step 3
        document.getElementById('step3').style.display = 'block';
        setActiveStep(3);
        var slotId = $(this).data('class-id');
        $.ajax({
            url: '/get-class-by-slot/' + slotId,
            method: 'GET',
            success: function (response) {
                $.each(response, function (index, classInfo) {
                    var fields = ['teacherName', 'days', 'startTime', 'endTime', 'teacherId'];
                    fields.forEach(function (field) {
                        $('#' + field).val(classInfo[field]);
                        $('.' + field).html(classInfo[field]);
                    });
                    if (classInfo.id) {
                        $('#classId').val(classInfo.id);
                    }
                    if (classInfo.price) {
                        $('#SessionPirce').val(classInfo.price);
                        $('.SessionPirce').html(classInfo.price);
                    }
                    // Combine start time and end time and display them together
                    if (classInfo.startTime && classInfo.endTime) {
                        $('#timeSlot').html(classInfo.startTime + ' - ' + classInfo.endTime).show();
                    } else {
                        $('#timeSlot').hide(); // Hide if the times are not available
                    }
                    if (classInfo.days) {
                        //console.log(classInfo.days);
                        var days = classInfo.days.toLowerCase();
                        var targetDate = getNextDateByDay(days);
                        var year = targetDate.getFullYear();
                        var month = String(targetDate.getMonth() + 1).padStart(2, '0'); // Months are 0-based, so add 1
                        var day = String(targetDate.getDate()).padStart(2, '0');
                        // Display the date in DD/MM/YYYY format in the .startDate element
                        $('.startDate').html(`${day}/${month}/${year}`);
                        // Set the value of #startDate input to the formatted date in YYYY-MM-DD format
                        $('#startDate').val(`${year}-${month}-${day}`);
                    }
                });
            },
            error: function () {
                $('#bookingFormContainer').html('An error occurred while fetching class data.');
            }
        });
    });
});




function getNextDateByDay(days) {
    // debugger;
    var daysofWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    var currentDate = new Date();
    var currentDayIndex = currentDate.getDay();
    var targetDayIndex = daysofWeek.indexOf(days);
    if (targetDayIndex === -1) return null; // Invalid day

    var dayDifference = targetDayIndex - currentDayIndex;
    if (dayDifference < 0) dayDifference += 7;

    currentDate.setDate(currentDate.getDate() + dayDifference);
    return currentDate;
}

// Discount popup js

$(function () {
    $('.bulkdiscount').on('click', function () {
        $('#bulkDiscountPopup').fadeIn();
    });

    $('.close-popup').on('click', function () {
        $('#bulkDiscountPopup').fadeOut();
    });

    $(document).on('click', function (e) {
        if ($(e.target).closest('.popup-content').length === 0 && !$(e.target).closest('.bulkdiscount').length) {
            $('#bulkDiscountPopup').fadeOut();
        }
    });
});

// Final amount calculation

$(function () {
    function calculateFinalAmount() {
        // debugger;
        var sessionCost = $('#SessionPirce').val();
        var numberofSessions = parseInt($('#noofsessions').val()) || 0;
        var discount = 0;
        var totalDiscount = 0;
        var totalAmount = sessionCost * numberofSessions;
        // Loop through the discounts array
        discounts.forEach(discount => {
            if (numberofSessions >= discount.minSessions) {
                totalDiscount = totalAmount * (discount.discountAmount / 100);
            }
        });
        // Add membership discount
        totalDiscount += (totalAmount * (membershipDiscount / 100));

        console.log(`Total Discount: ${totalDiscount}`);

        var finalAmount = totalAmount - totalDiscount;

        // Update the summary div with the calculated values
        $('#finalAmount').text(`$${finalAmount.toFixed(2)}`);

        // Update hidden input fields with the calculated values
        $('#finalAmountInput').val(finalAmount.toFixed(2));
        $('#totalDiscountInput').val(totalDiscount.toFixed(2));
        $('#totalAmountInput').val(totalAmount.toFixed(2));
    }
    $('#noofsessions').on('input', function () {
        calculateFinalAmount();
    });
    calculateFinalAmount();
});

// End Date Calculation


function calculateEndDate() {
    let numberofSessions = parseInt($('#noofsessions').val()) || 0;
    let frequency;
    //let frequency = $('input[name="frequency"]:checked').val();
    if ($('input[name="frequency"]:checked').length > 0) {
        // For radio buttons
        frequency = $('input[name="frequency"]:checked').val();
    } else {
        // For hidden input (group)
        frequency = $('#frequency').val();
    }
    let startDate = $('#startDate').val();
    console.log(numberofSessions + ' ' + frequency + ' ' + startDate);

    // Add your logic here to calculate the end date based on the number of sessions and frequency
    if (startDate && numberofSessions > 0 && frequency) {
        let start = new Date(startDate);
        let endDate;
        let scheduledates = []; // Array to store all dates between start and end
        switch (frequency) {
            case 'weekly':
                endDate = new Date(start.setDate(start.getDate() + (7 * (numberofSessions - 1))));
                break;
            case 'fortnightly':
                endDate = new Date(start.setDate(start.getDate() + (14 * (numberofSessions - 1))));
                break;
            default:
                return;
        }

        // Format the end date as YYYY-MM-DD
        let formattedEndDate = endDate.toISOString().split('T')[0];

        // Display the formatted end date
        $('#endDate').val(formattedEndDate);

        // Generate all dates between start date and end date
        // debugger;
        let currentDate = new Date(startDate);
        while (currentDate <= endDate) {
            scheduledates.push(formatDate(currentDate));
            currentDate.setDate(currentDate.getDate() + (frequency === 'weekly' ? 7 : 14));
        }
        console.log('All dates between start and end:', scheduledates);


        // You can also display these dates in a div or use them as needed
        //$('#scheduleOutput').html(scheduledates.map(date=>`<p>${date}</p>`).join(''));
        return scheduledates;
    }
    return [];
}

// Trigger calculation when the number of sessions changes or when any frequency option is changed
$('#noofsessions, input[name="frequency"], #startDate').on('input change', function () {
    calculateEndDate();
});

// Initial calculation on page load
calculateEndDate();

// Function to format date as DD/MM/YYYY
function formatDate(date) {
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based, so add 1
    var year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

//Payment form submission js

document.addEventListener('DOMContentLoaded', async function () {
    const stripe = Stripe('pk_test_51PqBU8H4Dau8eQBOxltl5rHiUv1joMfj6NFLX1fLtk15qvvsyga2ZH5P0qmjpZuqhyqmTZ9aL3GFZ18rjdC3V3kB00OBPleS7P'); // Replace with your Stripe publishable key
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('bookingForm');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Hide Step 3
        document.getElementById('step3').style.display = 'none';
        // Show Step 4
        document.getElementById('step4').style.display = 'block';
        setActiveStep(4);
        const scheduledates = calculateEndDate();

        // Collect booking details
        const classId = document.getElementById('classId').value;
        const classType = document.getElementById('classType').value;
        const studentId = document.getElementById('studentId').value;
        const studentName = document.getElementById('studentName').value;
        const studentEmail = document.getElementById('studentEmail').value;
        const studentPhone = document.getElementById('studentPhone').value;
        const teacherId = document.getElementById('teacherId').value;
        const teacherName = document.getElementById('teacherName').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;
        const days = document.getElementById('days').value;
        //const noOfStudent = document.querySelector('input[name="noofstudents"]:checked').value;
        let noOfStudent;
        if ($('input[name="noofstudents"]:checked').length > 0) {
            // For radio buttons
            noOfStudent = $('input[name="noofstudents"]:checked').val();
        } else {
            // For hidden input (group)
            noOfStudent = $('#noofstudents').val();
        }

        //const partnername = document.getElementById('partnername').value;
        let partnername = '';

        const partnernameElement = document.getElementById('partnername');
        if (partnernameElement) {
            partnername = partnernameElement.value;
        }
        let frequency;
        //let frequency = $('input[name="frequency"]:checked').val();
        if ($('input[name="frequency"]:checked').length > 0) {
            // For radio buttons
            frequency = $('input[name="frequency"]:checked').val();
        } else {
            // For hidden input (group)
            frequency = $('#frequency').val();
        }
        //  const frequency = document.querySelector('input[name="frequency"]:checked').value;
        const noOfSession = document.getElementById('noofsessions').value;
        const totalAmount = document.getElementById('totalAmountInput').value;
        const totalDiscount = document.getElementById('totalDiscountInput').value;
        const finalAmount = document.getElementById('finalAmountInput').value;
        // const endDate = calculateEndDate(startDate, frequency, noOfSession);
        // document.getElementById('endDate').value = endDate;

        // Get the client secret from your server
        const { clientSecret } = await fetch('/create-payment-intent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                // Add any additional data you want to send to the server
                classId: classId,
                classType: classType,
                studentId: studentId,
                studentName: studentName,
                studentEmail: studentEmail,
                studentPhone: studentPhone,
                teacherId: teacherId,
                teacherName: teacherName,
                startDate: startDate,
                endDate: endDate,
                startTime: startTime,
                endTime: endTime,
                days: days,
                noOfStudent: noOfStudent,
                partnername: partnername,
                frequency: frequency,
                noOfSession: noOfSession,
                totalAmount: totalAmount,
                totalDiscount: totalDiscount,
                finalAmount: finalAmount,
                scheduledates: scheduledates
            })
        }).then((r) => r.json());

        // Confirm the card payment
        const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: studentName,
                    email: studentEmail,
                    phone: studentPhone,
                },
            }
        });

        if (error) {
            // Display the error to the user
            document.getElementById('card-errors').textContent = error.message;
        } else {
            // The payment succeeded
            if (paymentIntent.status === 'succeeded') {
                // Store the booking data in the database along with the transaction ID
                const bookingData = await fetch('/store-booking', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        classId: classId,
                        classType: classType,
                        studentId: studentId,
                        studentName: studentName,
                        studentEmail: studentEmail,
                        studentPhone: studentPhone,
                        teacherId: teacherId,
                        teacherName: teacherName,
                        startDate: startDate,
                        endDate: endDate,
                        startTime: startTime,
                        endTime: endTime,
                        days: days,
                        noOfStudent: noOfStudent,
                        partnername: partnername,
                        frequency: frequency,
                        noOfSession: noOfSession,
                        totalAmount: totalAmount,
                        totalDiscount: totalDiscount,
                        finalAmount: finalAmount,
                        scheduledates: scheduledates,
                        transactionId: paymentIntent.id // Transaction ID
                    })
                });
                // window.location.href = '/payment-success';
                // Clear form data and reset input fields after successful payment
                form.reset();
                $('#card-element').empty(); // Clear card details
                $('#endDate').val('');
                $('#scheduleOutput').empty();
                // Display booking details on the same page
                document.getElementById('bookingDetails').innerHTML = `
                    <p>Day: ${days}</p>
                    <p>Time Slot: ${startTime} - ${endTime}</p>
                    <p>No.Of Sessions: ${noOfSession}</p>
                    <p>Final Amount: ${finalAmount}</p>
                `;
                $('#scheduleOutput').html(scheduledates.map(date => `<p>${date} - ${days}</p>`).join(''));
            } else {
                window.location.href = '/payment-failure';
            }
        }
    });
});

// Booking step wizard js

function setActiveStep(step) {
    // debugger;
    // Remove the active class from all steps
    document.querySelectorAll('.step').forEach(function (stepElem) {
        stepElem.classList.remove('active');
    });

    // Add the active class to the current step
    document.getElementById('step-' + step).classList.add('active');
}
setActiveStep(1);


$(document).on('click', '.backbtn1', function (e) {
    e.preventDefault();
    // Show Step 1
    document.getElementById('step1').style.display = 'block';
    // Hide Step 2
    document.getElementById('step2').style.display = 'none';
    setActiveStep(1);
});

$(document).on('click', '.backbtn2', function (e) {
    e.preventDefault();
    // Show Step 2
    document.getElementById('step2').style.display = 'block';
    // Hide Step 3
    document.getElementById('step3').style.display = 'none';
    setActiveStep(2);
});

