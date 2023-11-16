<script>
    let currentQuizIndex = 0;
    
    function val_edit(tabel, kolom, is_delete){
        $.ajax( {
            url: "../../models/training_m/fn_training_m.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tabel: tabel,
                id: kolom,
                is_delete: is_delete
            },
            success: function ( json ) {
                edit_val = json.data.r_val_edit;
            }
        } );
    }

    function formatTime(createdOn) {
        const now = new Date();
        const yesterday = new Date(now);
        yesterday.setDate(yesterday.getDate() - 1);

        if (createdOn >= yesterday) {
            
            const hours = createdOn.getHours();
            const minutes = createdOn.getMinutes();
            const amOrPm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 === 0 ? 12 : hours % 12;
            const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;

            return `Today ${formattedHours}:${formattedMinutes} ${amOrPm}`;
        } else {
            
            const year = createdOn.getFullYear();
            const month = (createdOn.getMonth() + 1).toString().padStart(2, '0');
            const day = createdOn.getDate().toString().padStart(2, '0');

            const hours = createdOn.getHours();
            const minutes = createdOn.getMinutes();
            const amOrPm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 === 0 ? 12 : hours % 12;
            const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;

            return `${day}.${month}.${year} ${formattedHours}:${formattedMinutes} ${amOrPm}`;
        }
    }

    function trainingDibuat(createdOn) {
        const now = new Date();
        const timeDifference = now - createdOn;
        
        const daysAgo = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
        const hoursAgo = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutesAgo = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));

        timeAgoText = '';
        if (daysAgo > 0) {
        timeAgoText += `${daysAgo}d `;
        }
        if (hoursAgo > 0) {
        timeAgoText += `${hoursAgo}h `;
        }
        if (minutesAgo > 0) {
        timeAgoText += `${minutesAgo}m `;
        }
    }

    function createButton(className, iconClass, dataId, dataFoto) {
        const button = document.createElement("a");
        button.href = "#";
        button.className = className;
        button.setAttribute("data-id", dataId);
        button.setAttribute("data-foto", dataFoto);
        button.innerHTML = `<i class="${iconClass}"></i>`;
        return button;
    }

    function updateCountdown() {
        const timerElement = document.getElementById('countdown-timer');
        const widget = document.querySelector('.widget');

        if (countdownMinutes >= 0) {
            timerElement.innerHTML = `${countdownMinutes} minutes ${countdownSeconds} seconds`;

            if (countdownMinutes === 1) {
            widget.classList.remove('navy-bg', 'yellow-bg');
            widget.classList.add('red-bg');
            } else if (countdownMinutes === 3) {
            widget.classList.remove('navy-bg', 'red-bg');
            widget.classList.add('yellow-bg');
            }

            if (countdownSeconds === 0) {
            countdownMinutes--;
            countdownSeconds = 59;
            } else {
            countdownSeconds--;
            }
        } else {
            timerElement.innerHTML = "Time's up!";
            clearInterval(countdownInterval);
            $('#materi').empty();
            $("#nextButton").hide();
            $("#prevButton").hide();
            $("#finishButton").hide();
            
            $("#materi-kiri").show();
            $("#judul-tr").show(); 
            $("#hide-tr").show();  
            $("#materi-sidebar").show(); 
            const materiKanan = $("#materi-kanan");
            materiKanan.removeClass("col-lg-12").addClass("col-lg-8");
        }
    }

    function genSubMateri(id_training_m) {
        $.ajax({
            url: "../../models/sub_materi_m/sub_materi_m_data.php",
            data: { id_training_m: id_training_m },
            dataType: 'json',
            success: function(data) {
                if (Array.isArray(data.data)) {
                    $('#sub_materi_content').empty();
                    const iboxContainer = document.getElementById("sub_materi_content");
                    data.data.forEach(function(item) {
                        let id_sub = item.DT_RowId;
                        // Create an iBox for each header (nama)
                        const title = document.createElement("div");
                        title.setAttribute("data-editor-id", id_sub); 
                        title.className = "ibox-title";
                        title.style.display = "flex";
                        title.style.justifyContent = "space-between";
                        const buttonsSub = [];
                        
                        const dropdownButton = document.createElement("div");
                        dropdownButton.classList.add("dropdown");

                        const dropdownToggle = document.createElement("button");
                        dropdownToggle.classList.add("btn", "btn-primary", "btn-sm", "dropdown-toggle");
                        dropdownToggle.setAttribute("type", "button");
                        dropdownToggle.setAttribute("data-toggle", "dropdown");
                        dropdownToggle.innerHTML = '<i class="fa fa-cogs">';

                        const dropdownMenu = document.createElement("div");
                        dropdownMenu.classList.add("dropdown-menu");

                        // Create and add individual buttons to the dropdown menu
                        const createButtonInDropdown = (className, icon, label, id = null) => {
                        const button = createButton(className, "", id); // No need to pass icon here
                        const listItem = document.createElement("li");

                        // Create a span for the label
                        const labelSpan = document.createElement("span");
                        labelSpan.textContent = label;

                        // Create an i element for the icon
                        const iconElement = document.createElement("i");
                        iconElement.className = icon;

                        // Set text color to white
                        button.style.color = 'white';

                        // Append the icon and label to the button
                        button.appendChild(iconElement);
                        button.appendChild(document.createTextNode(' ')); // Add a space between icon and label
                        button.appendChild(labelSpan);

                        listItem.appendChild(button);
                        dropdownMenu.appendChild(listItem);

                        return button;
                    };

                    // Example usage:
                    buttonsSub.push(createButtonInDropdown("btnCreatemateri btn btn-primary btn-sm", "fa fa-plus", "Create"));
                    buttonsSub.push(createButtonInDropdown("editSub btn btn-warning btn-sm", "fa fa-pencil", "Edit", item.sub_materi_m.id));
                    buttonsSub.push(createButtonInDropdown("removeSub btn btn-danger btn-sm", "fa fa-trash", "Remove", item.sub_materi_m.id));

                        // Add event listener to the first button in the array (assuming it's the create button)
                        buttonsSub[0].addEventListener("click", function() {
                            edtmateri_m.title('Create Materi').buttons(
                                {
                                    label: 'Submit',
                                    className: 'btn btn-primary',
                                    action: function() {
                                        this.submit();
                                    }
                                }
                            ).create();
                        });

                        // Action Edit Sub Materi
                        buttonsSub[1].addEventListener("click", function () {
                            var id = id_sub;
                            var match = id.match(/\d+/);
                            var number = match ? parseInt(match[0]) : null;

                            val_edit('sub_materi_m', number, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

                            // preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
                            edtsub_materi_m.on( 'preOpen', function( e, mode, action ) {
                                edtsub_materi_m.field('sub_materi_m.nama').val(edit_val.nama);
                            });
                            edtsub_materi_m.title('Edit Sub Materi').buttons(
                                {
                                    label: 'Submit',
                                    className: 'btn btn-primary', // Add the Bootstrap primary color
                                    action: function () {
                                        this.submit(); // This will submit the form
                                    }
                                }
                            ).edit(id);
                        });

                        // Action Delete Sub Materi
                        buttonsSub[2].addEventListener("click", function () {
                            var id = id_sub;
                            var match = id.match(/\d+/);
                            var number = match ? parseInt(match[0]) : null;

                            edtsub_materi_m.title('Delete Sub materi').buttons(
                                {
                                    label: 'Delete',
                                    className: 'btn btn-danger',
                                    action: function () {
                                        val_edit('sub_materi_m', number, 1);
                                        // location.reload();
                                        genSubMateri(id_training_m);
                                        edtsub_materi_m.close();
                                    }
                                }
                            ).message('Are you sure you want to delete this data?').remove(id);
                        });

                        // Append the dropdown elements to the title
                        dropdownButton.appendChild(dropdownToggle);
                        dropdownButton.appendChild(dropdownMenu);
                        title.appendChild(dropdownButton);

                        // Create the sub_materiTitle
                        const sub_materiTitle = document.createElement("h5");
                        sub_materiTitle.innerHTML = item.sub_materi_m.nama;

                        // Append the sub_materiTitle to the title
                        title.appendChild(sub_materiTitle);

                        // Create the "toggle" button (chevron icon)
                        const toggleButton = document.createElement("i");
                        toggleButton.className = "fa fa-chevron-up toggle-chevron";

                        title.appendChild(toggleButton);

                        // Create the iBox and add the title to it
                        const ibox = document.createElement("div");
                        ibox.className = "ibox";
                        ibox.appendChild(title);
                        title.setAttribute("data-id-sub_materi-m", item.sub_materi_m.id);
                        ibox.appendChild(title);

                        // Create the iBox content
                        const content = document.createElement("div");
                        content.className = "ibox-content";
                        ibox.appendChild(content);

                        // Add the iBox to the container
                        iboxContainer.appendChild(ibox);

                        // Add event listeners to change the color on hover
                        ibox.addEventListener('mouseenter', function() {
                            sub_materiTitle.style.color = 'blue';
                        });

                        ibox.addEventListener('mouseleave', function() {
                            sub_materiTitle.style.color = ''; // Reset the color to its default
                        });

                        // Add click event handler to load details via AJAX
                        const chevronIcon = ibox.querySelector('.toggle-chevron');
                        title.addEventListener("click", function() {
                            const id_sub_materi_m = this.getAttribute("data-id-sub_materi-m");
                            edtmateri_m.field('materi_m.id_sub_materi_m').val(id_sub_materi_m)

                            const contentElement = this.nextElementSibling;
                            if (contentElement.style.display === "none") {
                                sub_materiTitle.classList.add('ditekan'); 
                                
                                chevronIcon.classList.remove('fa-chevron-up');
                                chevronIcon.classList.add('fa-chevron-down');
                                // Load details via AJAX when the header (nama) is clicked
                                
                                edtmateri_m.on('postSubmit', function (e, json) {
                                    $("#materi-kanan").hide();
                                    genMateri(id_sub_materi_m, contentElement);
                                });
                                
                                genMateri(id_sub_materi_m, contentElement);
                            } else {
                                sub_materiTitle.classList.remove('ditekan'); 
                                contentElement.style.display = "none";
                                chevronIcon.classList.remove('fa-chevron-down');
                                chevronIcon.classList.add('fa-chevron-up');
                            }
                        });
                    });
                } else {
                    // console.log("No data available.");
                }
            },
            error: function() {
                // console.log("Error fetching data.");
            }
        });
    }

    function genMateri(id_sub_materi_m, contentElement) {
        $.ajax({
            url: "../../models/sub_materi_m/materi_m_data.php",
            data: { id_sub_materi_m: id_sub_materi_m },
            dataType: 'json',
            success: function(data) {
                var isi = data.data.length;
                
                if (Array.isArray(data.data)) {
                    let materiList = "<ul>";

                    data.data.forEach(function(item) {
                        let materi = item.materi_m;
                        let id = item.DT_RowId;
                        const createdOn_materi = new Date(materi.created_on);

                        trainingDibuat(createdOn_materi);
                        formatTime(createdOn_materi);
                        const h3silabus = document.createElement("h3");
                        h3silabus.setAttribute("for", `sub-sub_materi-checkbox-${materi.id}`);
                        cekJudul = `
                            <div class="row">
                                <div class="col-md-2">
                                    ${materi.jenis == 1
                                        ? `<i class="fa fa-film"></i>`
                                        : `<i class="fa fa-pencil-square-o"></i>`
                                    }
                                </div>
                                <div class="col-md-10">
                                    ${materi.nama}
                                    <input id="sub-sub_materi-checkbox-${materi.id}" type="checkbox" class="sub-sub_materi-checkbox" value="${materi.id}">
                                </div>
                            </div>
                        `;
                        h3silabus.innerHTML = cekJudul;
                       
                        materiList += 
                        `
                        <div class="materi_panel" data-editor-id="${id}">
                            <small class="float-right">${timeAgoText} ago</small>
                            <strong>
                                    ${h3silabus.outerHTML}
                            </strong>
                            <div class="tr-up">
                                <div>${materi.keterangan}</div>
                                <small class="text-muted">${formatTime(createdOn_materi)} - ${createdOn_materi.toLocaleDateString()}</small>		
                                <br>					
                                <br>					
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#" class="edit btn btn-primary btn-sm" data-id="${materi.id}"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="remove btn btn-danger btn-sm" data-id="${materi.id}"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>	
                        </div>
                        <hr>
                        `
                        ;
                    });

                    materiList += "</ul>";

                    contentElement.innerHTML = materiList;
                    contentElement.style.display = "block";

                    // Add a click event listener to capture the selected materi.id values
                    const checkboxes = document.querySelectorAll('.sub-sub_materi-checkbox');
                    let lastClickedCheckbox = null;

                    checkboxes.forEach(function(checkbox) {
                        checkbox.addEventListener('click', function() {
                            $('#judul').empty();
                            $("#materi-kanan").show();
                            const materiVideo = document.getElementById('materi');
                            const previousH3 = lastClickedCheckbox ? lastClickedCheckbox.closest('h3') : null;

                            if (this !== lastClickedCheckbox) {
                                const h3silabus = this.closest('h3');
                                // Uncheck previously selected checkbox and remove class
                                if (lastClickedCheckbox) {
                                    lastClickedCheckbox.checked = false;
                                    if (previousH3) {
                                        previousH3.classList.remove('ditekan');
                                    }
                                }

                                // Check the current checkbox
                                this.checked = true;
                                lastClickedCheckbox = this;

                                const subsub_materiId = this.value;
                                // console.log(`Selected materi.id: ${subsub_materiId}`);

                                // Update the <h3> text inside the #materi div
                                data.data.forEach(function(item) {
                                    let materi = item.materi_m;
                                    
                                    if (materi.id == subsub_materiId) {
                                        var link_yt = materi.link_yt;
                                        var jenis_materi = materi.jenis;
                                        var video_yt = '';
                                        var button_quiz = '';
                                        // preOpen Quiz
                                        edtquiz_m.on( 'preOpen', function( e, mode, action ) {
                                            // console.log(materi.id);
                                            edtquiz_m.field('quiz_m.id_materi_m').val(materi.id)
                                            if (materi.tipe_quiz == "Essay") {
                                                edtquiz_m.field('quiz_m.nama').label("Soal Essay <sup class='text-danger'>*<sup>")
                                            }

                                            if (materi.tipe_quiz == "Multiple Choice") {
                                                var customSelect = [
                                                    { "label": "A", "value": 1 },
                                                    { "label": "B", "value": 2 },
                                                    { "label": "C", "value": 3 },
                                                    { "label": "D", "value": 4 }
                                                ];

                                                edtquiz_m.field('quiz_m.jawaban_benar').update(customSelect);
                                                edtquiz_m.field('quiz_m.nama').label("Soal Multiple Choice <sup class='text-danger'>*<sup>")
                                                edtquiz_m.field('quiz_m.jawaban_a').show()
                                                edtquiz_m.field('quiz_m.jawaban_b').show()
                                                edtquiz_m.field('quiz_m.jawaban_c').show()
                                                edtquiz_m.field('quiz_m.jawaban_d').show()
                                                edtquiz_m.field('quiz_m.jawaban_benar').show()

                                                edtquiz_m.field('quiz_m.jawaban_a').val()
                                                edtquiz_m.field('quiz_m.jawaban_b').val()
                                                edtquiz_m.field('quiz_m.jawaban_c').val()
                                                edtquiz_m.field('quiz_m.jawaban_d').val()
                                                edtquiz_m.field('quiz_m.jawaban_benar').val()                                                
                                            } else {
                                                edtquiz_m.field('quiz_m.jawaban_a').hide()
                                                edtquiz_m.field('quiz_m.jawaban_b').hide()
                                                edtquiz_m.field('quiz_m.jawaban_c').hide()
                                                edtquiz_m.field('quiz_m.jawaban_d').hide()
                                                edtquiz_m.field('quiz_m.jawaban_benar').hide()

                                                edtquiz_m.field('quiz_m.jawaban_a').val('')
                                                edtquiz_m.field('quiz_m.jawaban_b').val('')
                                                edtquiz_m.field('quiz_m.jawaban_c').val('')
                                                edtquiz_m.field('quiz_m.jawaban_d').val('')
                                                edtquiz_m.field('quiz_m.jawaban_benar').val('')
                                            }
                                            
                                            if (materi.tipe_quiz == "True/False") {
                                                edtquiz_m.field('quiz_m.nama').label("Soal True/False <sup class='text-danger'>*<sup>")
                                                var customSelect = [
                                                    { "label": "True", "value": 1 },
                                                    { "label": "False", "value": 2 }
                                                ];

                                                edtquiz_m.field('quiz_m.jawaban_benar').update(customSelect);
                                                edtquiz_m.field('quiz_m.jawaban_benar').show()
                                                edtquiz_m.field('quiz_m.jawaban_benar').val() 
                                            }
                                        });
                                        
                                        // preSubmit quiz
                                        edtquiz_m.on( 'preSubmit', function (e, data, action) {
                                            if(action != 'remove'){
                                                if(action == 'create'){
                                                    // BEGIN of validasi quiz_m.nama
                                                    if ( ! edtquiz_m.field('quiz_m.nama').isMultiValue() ) {
                                                        nama = edtquiz_m.field('quiz_m.nama').val();
                                                        if(!nama || nama == ''){
                                                            edtquiz_m.field('quiz_m.nama').error( 'Wajib diisi!' );
                                                        }
                                                        
                                                        // BEGIN of cek unik quiz_m.nama
                                                        if(action == 'create'){
                                                            id_quiz_m = 0;
                                                        }
                                                        
                                                        $.ajax( {
                                                            url: '../../../helpers/validate_fn_unique.php',
                                                            dataType: 'json',
                                                            type: 'POST',
                                                            async: false,
                                                            data: {
                                                                table_name: 'quiz_m',
                                                                nama_field: 'nama',
                                                                nama_field_value: '"'+nama+'"',
                                                                id_transaksi: id_quiz_m
                                                            },
                                                            success: function ( json ) {
                                                                if(json.data.count == 1){
                                                                    edtquiz_m.field('quiz_m.nama').error( 'Data tidak boleh kembar!' );
                                                                }
                                                            }
                                                        } );
                                                        // END of cek unik quiz_m.nama
                                                    }
                                                    // END of validasi quiz_m.nama

                                                    if (materi.tipe_quiz == "Multiple Choice") {
                                                        jawaban_a = edtquiz_m.field('quiz_m.jawaban_a').val();
                                                        if(!jawaban_a || jawaban_a == ''){
                                                            edtquiz_m.field('quiz_m.jawaban_a').error( 'Wajib diisi!' );
                                                        }

                                                        jawaban_b = edtquiz_m.field('quiz_m.jawaban_b').val();
                                                        if(!jawaban_b || jawaban_b == ''){
                                                            edtquiz_m.field('quiz_m.jawaban_b').error( 'Wajib diisi!' );
                                                        }

                                                        jawaban_c = edtquiz_m.field('quiz_m.jawaban_c').val();
                                                        if(!jawaban_c || jawaban_c == ''){
                                                            edtquiz_m.field('quiz_m.jawaban_c').error( 'Wajib diisi!' );
                                                        }

                                                        jawaban_d = edtquiz_m.field('quiz_m.jawaban_d').val();
                                                        if(!jawaban_d || jawaban_d == ''){
                                                            edtquiz_m.field('quiz_m.jawaban_d').error( 'Wajib diisi!' );
                                                        }

                                                        jawaban_benar = edtquiz_m.field('quiz_m.jawaban_benar').val();
                                                        if(!jawaban_benar || jawaban_benar == ''){
                                                            edtquiz_m.field('quiz_m.jawaban_benar').error( 'Wajib diisi!' );
                                                        } 
                                                    }
                                                    if (materi.tipe_quiz == "True/False") {
                                                        jawaban_benar = edtquiz_m.field('quiz_m.jawaban_benar').val();
                                                        if(!jawaban_benar || jawaban_benar == ''){
                                                            edtquiz_m.field('quiz_m.jawaban_benar').error( 'Wajib diisi!' );
                                                        } 
                                                    }
                                                }
                                            }
                                            
                                            if ( edtquiz_m.inError() ) {
                                                return false;
                                            }
                                        });
                                        
                                        if (jenis_materi == 1) {
                                            $("#nextButton").hide();
                                            $("#prevButton").hide();
                                            if (link_yt !== null && link_yt !== undefined) {
                                                var match = link_yt.match(/[?&]v=([^&]+)/);
                                                if (match) {
                                                    link = match[1];
                                                    video_yt = '<iframe id="videoFrame" width="100%" height="480px" src="https://www.youtube.com/embed/'+link+'" frameborder="0" allowfullscreen></iframe>';
                                                }
                                            }
                                            materiVideo.innerHTML = video_yt;
                                            
                                            // Set the video title
                                            var h3Title = document.getElementById('judul');
                                            h3Title.textContent = materi.nama;
                                        } else {
                                            $('#materi').empty();
                                            const button_quiz = createButton("btnCreatemateri btn btn-primary btn-sm", "fa fa-plus");
                                            const button_start_quiz = createButton("btnStart_quiz btn btn-primary btn-sm", "fa fa-fx");
                                            
                                            button_start_quiz.innerHTML = `Start`;
                                            materiVideo.appendChild(button_quiz);
                                            materiVideo.appendChild(button_start_quiz);

                                            let isCountdownStarted = false;

                                            var h3Title = document.getElementById('judul');
                                            h3Title.textContent = materi.nama;
                                            
                                            button_start_quiz.addEventListener("click", function() {
                                                $('#nextPrevMultiQuiz').html(`
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <button id="prevButton" class="btn btn-primary">Previous</button>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button id="nextButton" class="btn btn-primary">Next</button>
                                                            <button id="finishButton" class="btn btn-success">Finish</button>
                                                        </div>
                                                    </div>
                                                `);
                                                
                                                $("#nextButton").show();
                                                $("#prevButton").show();
                                                const materiKanan = $("#materi-kanan");
                                                materiKanan.removeClass("col-lg-8").addClass("col-lg-12");
                                                $("#materi-kiri").hide();
                                                $("#judul-tr").hide() 
                                                $("#hide-tr").hide() 
                                                $("#materi-sidebar").hide() 
                                                countdownMinutes = materi.durasi; // Set Menit Countdown, bisa diambilkan di quiz
                                                countdownSeconds = 0; // Reset countdown seconds
                                                // console.log('kawoawkokawokawo');
                                                
                                                // Set the video title
                                                h3Title.innerHTML = `
                                                    <div class="widget navy-bg p-sm text-center">
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <h2 class="m-xs" style="font-weight: bold;">${materi.nama}</h2>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="m-b-sm">
                                                                    <i class="fa fa-clock-o fa-2x"></i>
                                                                    <h4 class="m-xs" id="countdown-timer"></h4>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                    <br>
                                                `;
                                                
                                                updateCountdown(); // Start the countdown
                                                countdownInterval = setInterval(updateCountdown, 1000);
                                                window.addEventListener("beforeunload", function (e) {
                                                    e.returnValue = "Leaving this page will stop the countdown. Are you sure?";
                                                });

                                                materiVideo.innerHTML = `
                                                    <div class="row">
                                                        <div class="col-md-12" id="KontenQuizContainer">
                                                        </div>
                                                    </div>
                                                `;
                                                $.ajax({
                                                    url: "../../models/quiz_m/quiz_m_data.php",
                                                    data: { id_materi_m: materi.id },
                                                    dataType: 'json',
                                                    success: function (data) {
                                                        if (Array.isArray(data.data)) {
                                                            const totalQuizzes = data.data.length;

                                                            // Event listener for the next button
                                                            document.getElementById('nextButton').addEventListener('click', function () {
                                                                if (currentQuizIndex < totalQuizzes - 1) {
                                                                    currentQuizIndex++;
                                                                    if (materi.tipe_quiz == "Multiple Choice") {
                                                                        displayQuiz(data, currentQuizIndex);
                                                                    }
                                                                    if (materi.tipe_quiz == "True/False") {
                                                                        displayTrueFalse(data, currentQuizIndex);
                                                                    }
                                                                    updateButtonVisibility(totalQuizzes);
                                                                }
                                                            });

                                                            // Event listener for the previous button
                                                            document.getElementById('prevButton').addEventListener('click', function () {
                                                                if (currentQuizIndex > 0) {
                                                                    currentQuizIndex--;
                                                                    if (materi.tipe_quiz == "Multiple Choice") {
                                                                        displayQuiz(data, currentQuizIndex);
                                                                    }
                                                                    if (materi.tipe_quiz == "True/False") {
                                                                        displayTrueFalse(data, currentQuizIndex);
                                                                    }
                                                                    updateButtonVisibility(totalQuizzes);
                                                                }
                                                            });

                                                            document.getElementById('finishButton').addEventListener('click', function () {
                                                                // Call a function to handle finishing the quiz
                                                                finishQuiz();
                                                            });

                                                            // Initial display
                                                            if (materi.tipe_quiz == "Multiple Choice") {
                                                                displayQuiz(data, currentQuizIndex);
                                                            }
                                                            if (materi.tipe_quiz == "True/False") {
                                                                displayTrueFalse(data, currentQuizIndex);
                                                            }
                                                            updateButtonVisibility(totalQuizzes);
                                                        }
                                                    }
                                                });
                                            });

                                            button_quiz.addEventListener("click", function() {
                                                edtquiz_m.title('Create Quiz').buttons(
                                                    {
                                                        label: 'Submit',
                                                        className: 'btn btn-primary',
                                                        action: function() {
                                                            this.submit();
                                                        }
                                                    }
                                                ).create();
                                            });
                                        }
                                        // Set the video title
                                        // var h3Title = document.getElementById('judul');
                                        // h3Title.textContent = materi.nama;

                                        h3silabus.classList.add('ditekan');
                                    }
                                });
                            } else {
                                // Deselect the currently selected checkbox
                                this.checked = false;
                                lastClickedCheckbox = null;
                                $("#materi-kanan").hide();
                                $('#materi').empty();
                                $('#judul').empty();
                                
                                const previousH3 = this.closest('h3');
                                previousH3.classList.remove('ditekan');
                                
                            }
                        });
                    });
                } else {
                    contentElement.innerHTML = "<p>Data structure is invalid.</p>";
                    contentElement.style.display = "block";
                }
            },
            error: function() {
                contentElement.innerHTML = "<p>Failed to load details.</p>";
                contentElement.style.display = "block";
            }
        });
        
        // Remove
        $(document).on('click', '.materi_panel a.remove', function () {
            var id = $(this).data('id');

            if (confirm('Anda yakin ingin menghapus data ini?')) {
                val_edit('materi_m', id, 1);
                genMateri(id_sub_materi_m, contentElement);
            }
        });
    }

    // Multiple Choice
    const selectedAnswers = [];

    function generateListItems(choices, questionIndex) {
        let leftColumnHTML = '';
        let rightColumnHTML = '';

        // Split choices into two halves
        const halfLength = Math.ceil(choices.length / 2);
        const leftChoices = choices.slice(0, halfLength);
        const rightChoices = choices.slice(halfLength);

        // Generate HTML for left column
        leftChoices.forEach(choice => {
            leftColumnHTML += generateListItemHTML(choice, questionIndex);
        });

        // Generate HTML for right column
        rightChoices.forEach(choice => {
            rightColumnHTML += generateListItemHTML(choice, questionIndex);
        });

        return `
            <div class="col-md-6">
                <ul class="todo-list m-t">${leftColumnHTML}</ul>
            </div>
            <div class="col-md-6">
                <ul class="todo-list m-t">${rightColumnHTML}</ul>
            </div>
        `;
    }

    // ini untuk Multiple Choice
    function generateListItemHTML(choice, questionIndex) {
        const isChecked = selectedAnswers[questionIndex] === choice.value ? 'checked' : '';
        return `
            <li>
                <input class="i-checks" type="checkbox" name="answer" id="choice${choice.id}" value="${choice.value}" ${isChecked}>
                <span class="m-l-xs">${choice.label}</span>
            </li>
        `;
    }

    // ini untuk Multiple Choice
    function displayQuiz(data, index) {
        const valQuiz = data.data[index].quiz_m;
        const answerChoices = [
            { id: 'A', value: 1, label: valQuiz.jawaban_a },
            { id: 'B', value: 2, label: valQuiz.jawaban_b },
            { id: 'C', value: 3, label: valQuiz.jawaban_c },
            { id: 'D', value: 4, label: valQuiz.jawaban_d },
            // Add more choices as needed
        ];

        // Generate HTML for choices in two columns
        let choicesHTML = '<div class="row">' + generateListItems(answerChoices, index) + '</div>';

        // Update the current quiz display
        document.querySelector('#KontenQuizContainer').innerHTML = `
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="text-center">${valQuiz.nama}</h3>
                    <form id="quizForm">
                        ${choicesHTML}
                    </form>
                </div>
            </div>
        `;

        // Add event listeners to checkboxes after updating the quiz display
        addCheckboxEventListeners(index);
    }

    function addCheckboxEventListeners(questionIndex) {
        const checkboxes = document.querySelectorAll('.i-checks');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                // Update the selected answer for the current question
                selectedAnswers[questionIndex] = checkbox.checked ? parseInt(checkbox.value) : null;

                // Uncheck all checkboxes except the current one
                checkboxes.forEach(otherCheckbox => {
                    if (otherCheckbox !== checkbox) {
                        otherCheckbox.checked = false;
                    }
                });
            });
        });
    }

    function finishQuiz() {
        // Log the selected answers for each question
        console.log('Selected Answers:', selectedAnswers);
        // You can add additional logic here, such as showing a summary or processing the answers
        alert('Quiz finished! Check the console for selected answers.');
        
        const timerElement = document.getElementById('countdown-timer');
        timerElement.innerHTML = "Time's up!";
        clearInterval(countdownInterval);
        $('#materi').empty();
        $("#nextButton").hide();
        $("#prevButton").hide();
        $("#finishButton").hide();
        
        $("#materi-kiri").show();
        $("#judul-tr").show(); 
        $("#hide-tr").show();  
        $("#materi-sidebar").show(); 
        const materiKanan = $("#materi-kanan");
        materiKanan.removeClass("col-lg-12").addClass("col-lg-8");
        
    }

    function updateButtonVisibility(totalQuizzes) {
        // Show/hide the prevButton based on currentQuizIndex
        const prevButton = document.getElementById('prevButton');
        if (currentQuizIndex === 0) {
            prevButton.style.display = 'none';
        } else {
            prevButton.style.display = 'block';
        }

        // Show/hide the nextButton and finishButton based on currentQuizIndex
        const nextButton = document.getElementById('nextButton');
        const finishButton = document.getElementById('finishButton');
        if (currentQuizIndex === totalQuizzes - 1) {
            nextButton.style.display = 'none';
            finishButton.style.display = 'block';
        } else {
            nextButton.style.display = 'block';
            finishButton.style.display = 'none';
        }
    }

    // True False
    
    function genTruFalseItem(choices, questionIndex) {
        let leftColumnHTML = '';
        let rightColumnHTML = '';

        // Split choices into two halves
        const halfLength = Math.ceil(choices.length / 2);
        const leftChoices = choices.slice(0, halfLength);
        const rightChoices = choices.slice(halfLength);

        // Generate HTML for left column
        leftChoices.forEach(choice => {
            leftColumnHTML += genHTMLTrueFalse(choice, questionIndex);
        });

        // Generate HTML for right column
        rightChoices.forEach(choice => {
            rightColumnHTML += genHTMLTrueFalse(choice, questionIndex);
        });

        return `
            <div class="col-md-6">
                <ul class="todo-list m-t">${leftColumnHTML}</ul>
            </div>
            <div class="col-md-6">
                <ul class="todo-list m-t">${rightColumnHTML}</ul>
            </div>
        `;
    }

    // ini untuk Multiple Choice
    function genHTMLTrueFalse(choice, questionIndex) {
        const isChecked = selectedAnswers[questionIndex] === choice.value ? 'checked' : '';
        return `
            <li>
                <input class="i-checks" type="checkbox" name="answer" id="choice${choice.id}" value="${choice.value}" ${isChecked}>
                <span class="m-l-xs">${choice.label}</span>
            </li>
        `;
    }

    // ini untuk Multiple Choice
    function displayTrueFalse(data, index) {
        const valQuiz = data.data[index].quiz_m;
        const answerChoices = [
            { id: 'A', value: 1, label: 'True' },
            { id: 'B', value: 2, label: 'False' },
            // Add more choices as needed
        ];

        // Generate HTML for choices in two columns
        let choicesHTML = '<div class="row">' + genTruFalseItem(answerChoices, index) + '</div>';

        // Update the current quiz display
        document.querySelector('#KontenQuizContainer').innerHTML = `
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="text-center">${valQuiz.nama}</h3>
                    <form id="quizForm">
                        ${choicesHTML}
                    </form>
                </div>
            </div>
        `;

        // Add event listeners to checkboxes after updating the quiz display
        addCheckboxEventListeners(index);
    }
</script>