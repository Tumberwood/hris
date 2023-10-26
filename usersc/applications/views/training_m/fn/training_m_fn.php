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
            // Display in H:MM AM/PM format
            const hours = createdOn.getHours();
            const minutes = createdOn.getMinutes();
            const amOrPm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 === 0 ? 12 : hours % 12;
            const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;

            return `Today ${formattedHours}:${formattedMinutes} ${amOrPm}`;
        } else {
            // Display in DD.MM.YYYY format
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

    function createButton(className, iconClass, dataId) {
        const button = document.createElement("a");
        button.href = "#";
        button.className = className;
        button.setAttribute("data-id", dataId);
        button.innerHTML = `<i class="${iconClass}"></i>`;
        return button;
    }
</script>