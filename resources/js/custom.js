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

        $('#data1').html('<strong>Teacher Name:</strong> ' + teacherName + '<br><strong>Days:</strong> ' + days + '<br><strong>Styles:</strong><br>' + styles);

        // Fetch class data using AJAX
        $.ajax({
            url: '/get-classes/' + teacherId,
            method: 'GET',
            success: function (response) {
                var groupedData = {};

                // Group classes by day
                $.each(response, function (index, classInfo) {
                    if (!groupedData[classInfo.days]) {
                        groupedData[classInfo.days] = [];
                    }
                    groupedData[classInfo.days].push(classInfo);
                });

                var classData = '';

                $.each(groupedData, function (day, classes) {
                    classData += '<strong>Days:</strong> ' + day + '<br>';
                    $.each(classes, function (index, classInfo) {
                        classData += '<div class="my-3"><strong>Time Slot:</strong> ' + classInfo.startTime + '-' + classInfo.endTime +
                            ' <a href="#" class="btn btn-primary book-now" data-class-id="' + classInfo.id + '" data-teacher-id="' + classInfo.teacherId + '">Book Now</a></div>';
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
        var slotId = $(this).data('class-id');
        $.ajax({
            url: '/get-class-by-slot/' + slotId,
            method: 'GET',
            success: function (response) {
                $.each(response, function (index, classInfo) {
                    var fields = ['teacherName', 'days', 'startTime', 'endTime'];
                    fields.forEach(function (field) {
                        $('#' + field).val(classInfo[field]);
                        $('.' + field).html(classInfo[field]);
                    });
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
        var sessionCost = 10;
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