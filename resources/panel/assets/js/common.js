window.copyToClipboard = function(element) {
    const textToCopy = element.innerText;

    navigator.clipboard.writeText(textToCopy).then(() => {
        // alert('Copied: ' + textToCopy);
        showCopiedMessage(element);
        element.classList.add('copied-pulse');

        setTimeout(() => {
            element.classList.remove('copied-pulse');
        }, 3500);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

function showCopiedMessage(targetElement) {
    const message = document.createElement('div');
    message.innerText = 'Copied!';
    message.style.position = 'absolute';
    message.style.background = '#333';
    message.style.color = '#fff';
    message.style.padding = '5px 10px';
    message.style.borderRadius = '4px';
    message.style.fontSize = '12px';
    message.style.zIndex = 1000;
    message.style.top = (targetElement.getBoundingClientRect().top - 35 + window.scrollY) + 'px';
    message.style.left = (targetElement.getBoundingClientRect().left + window.scrollX) + 'px';
    message.style.transition = 'opacity 0.3s';
    message.style.opacity = '1';

    document.body.appendChild(message);

    setTimeout(() => {
        message.style.opacity = '0';
        setTimeout(() => message.remove(), 400);
    }, 3000);
}

document.addEventListener('alpine:init', function(event) {
    Alpine.data('alert', () => {

        return {
            showCancellationReason(cancellation_reason) {
                Swal.fire({
                    title: 'Cancellation Reason:',
                    text: cancellation_reason,
                    icon: 'info',
                });
            },
            showNarration(narration) {
                Swal.fire({
                    title: 'Narration:',
                    text: narration,
                    icon: 'info',
                });
            },
            showConfirmationAlert(id, event, currentStatus) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (event.target.value === 'cancelled') {
                            this.getCancelReason(id, event, currentStatus);
                        } else {
                            this.$wire.changeStatus(id, event.target.value);
                        }
                    } else {
                        event.target.value = currentStatus;
                    }
                });
            },
            showLogoutConfirmation(text) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$wire.logout();
                    }
                });
            },
            getCancelReason(id, event, status) {
                Swal.fire({
                    title: "Enter the reason for cancellation",
                    input: "text",
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Submit",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    preConfirm: (value) => {
                        if (!value) {
                            Swal.showValidationMessage('Please enter the reason for cancellation');
                            return false;
                        }
                        if (value.length < 3) {
                            Swal.showValidationMessage('Minimum 3 characters required');
                            return false;
                        }
                        if (value.length > 255) {
                            Swal.showValidationMessage('Maximum 255 characters required');
                            return false;
                        }
                        return value;
                    },
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$wire.changeStatus(id, event.target.value, result.value);
                    } else {
                        event.target.value = status;
                    }
                });
            }
        }
    });
});