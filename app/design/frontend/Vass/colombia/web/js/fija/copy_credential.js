function targetCredentialCard() {
    return {
        documentNumber: "",
        isCardChangeSide: false,
    };
}

function backFlipCard() {
    this.isCardChangeSide = true;
}

function frontFlipCard() {
    this.isCardChangeSide = false;
}

function addPoints() {
    let copyDocument = ""

    if(this.documentNumber.length < 1) {
        copyDocument = new Intl.NumberFormat('en-US').format("1234567890");
    }else{
        numberFormat = new Intl.NumberFormat('en-US').format(this.documentNumber)
        copyDocument =  numberFormat.replace(/,/g, '.');
    }
    
    this.$refs.refDocumentNumber.innerHTML = copyDocument
}

function changeDate(event) {
    var valueInputDate = event.target.value;
    let dateReplace = new Date(valueInputDate.replace(/-+/g, "/"));
    console.log('valueInputDate: '+valueInputDate);
    let optionsToNewDate = {
        year: "numeric",
        month: "short",
        day: "numeric",
    };

    formatDate = dateReplace.toLocaleDateString("es-CO", optionsToNewDate)

    const jsDataReplace = document.querySelector(".js-date-replace");

    jsDataReplace.innerHTML = formatDate.replace("de", "").replace("de", "");
}
