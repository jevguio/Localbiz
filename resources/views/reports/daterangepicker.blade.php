 
    <div  style="width:100%" id="date-range">    
    </div>
    <script>
        let startDate,endDate;
        const dateRange = document.getElementById('date-range');
        const dateRangePicker=document.createElement('input');
        dateRangePicker.setAttribute('type','text');
        dateRangePicker.setAttribute('id','dateRangePicker');
        dateRange.appendChild(dateRangePicker);
        const dateRangeflatpickr= flatpickr(dateRangePicker, {
            mode: "range",
            dateFormat: "Y-m-d",
            clickOpens: false,
            onOpen: function(selectedDates, dateStr, instance) {
                    const overlay = document.getElementById('overlay');
                    overlay.style.display="block"; 

            },
            onChange: function(selectedDates, dateStr, instance) { 
                if (selectedDates.length === 2) {
                     startDate = selectedDates[0];
                     endDate = selectedDates[1]; 
                        buttonContainer.style.display="block";
                        const DateStartEnd = document.getElementById('DateStartEnd');
                        const startFormatted = startDate.toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        const endFormatted = endDate.toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        // Display formatted date
                        DateStartEnd.innerHTML = "Date: " + startFormatted + " - " + endFormatted;
                        
                        const overlay = document.getElementById('overlay');
                        overlay.style.display="block"; 
                        dateRangeflatpickr.open(); 
                }
            },
            onClose: function(selectedDates, dateStr, instance) {
                 
                 
                const overlay = document.getElementById('overlay');
                overlay.style.display="none"; 
                buttonContainer.style.display="none";
            },
        });
 
        const buttonContainer = document.createElement("div");
        buttonContainer.style.display="none";
        const confirmButton = document.createElement("button");
        confirmButton.id = "confirm-picker";
        confirmButton.textContent = "Confirm";
        confirmButton.addEventListener("click", function() { 
            
            const invent = document.getElementById('Product_Management');
            invent.style.display="block"; 
            const overlay = document.getElementById('overlay');
            overlay.style.display="none"; 
            dateRangeflatpickr.clear();
            dateRangeflatpickr.close();
            
        });

        document.getElementById("overlay").addEventListener("click", function(event) {
            event.stopPropagation();
        });
        const cancelButton = document.createElement("button");
        cancelButton.id = "cancel-picker";
        cancelButton.textContent = "Cancel"; 
        cancelButton.classList.add("cancel-button");
        cancelButton.addEventListener("click", function() {
            dateRangeflatpickr.clear();
            document.getElementById("date-range").value = "";
        });
        const collection = document.getElementsByClassName("flatpickr-calendar");
        buttonContainer.classList.add("button-group");
        buttonContainer.appendChild(confirmButton);
        buttonContainer.appendChild(cancelButton); 
        collection[0].appendChild(buttonContainer);
    </script> 