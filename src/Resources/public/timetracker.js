
window.addEvent('domready', function() {
    var selectElement = document.getElementById('kunde');   // Selektiere das <select> Element

    if (selectElement) {                                    // IF( Element gefunden )
        selectElement.addEvent('change', function() {       //   Füge das onchange Attribut hinzu
            this.form.submit();                             //   onChange löse Submit aus
        });
    }
});

