<script>
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
            
            $("#materi-kiri").show();
            $("#judul-tr").show(); 
            $("#hide-tr").show();  
            $("#materi-sidebar").show(); 
            const materiKanan = $("#materi-kanan");
            materiKanan.removeClass("col-lg-12").addClass("col-lg-8");
        }
    }

</script>