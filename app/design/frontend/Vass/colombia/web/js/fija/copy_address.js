function targetCopyAddress() {
    return {
        modelCopyAddress: "",
        numberOneAddress: "",
        numberTwoAddress: "",
        placaDireccion: "",
        direccion:"",
        numbersAddressHide: false,
        isSkeletonHide: false,
    };
}

function changeAddressCopy() {
    this.isSkeletonHide = true;

    if (
        this.modelCopyAddress == "Otro" ||
        this.modelCopyAddress == "Kilometro"
    ) {
        this.numbersAddressHide = true;
        this.$refs.copyAddress.innerHTML = ` `;
        this.$refs.refNumberOneAddress.innerHTML = ` `;
        this.$refs.refNumberTwoAddress.innerHTML = ` `;
        this.$refs.refPlacaDireccion.innerHTML = ` `;
        this.numberOneAddress = ` `;
        this.numberTwoAddress = ` `;
        this.placaDireccion = ` `;
    } else {
        this.numbersAddressHide = false;
        this.$refs.refNumberOneAddress.innerHTML = ` `;
        this.$refs.refNumberTwoAddress.innerHTML = ` `;
        this.$refs.refPlacaDireccion.innerHTML = ` `;
        this.numberOneAddress = ` `;
    }
}

function addSimbolPlus() {
    this.isSkeletonHide = true;

    if (this.$refs.refNumberOneAddress.innerHTML.length <= 0) {
        this.$refs.refNumberOneAddress.innerHTML = ``;
    } else {
        this.$refs.refNumberOneAddress.innerHTML = `${this.numberOneAddress} #`;
       
    }
}

function addSimbolMinor() {
    if (this.$refs.refPlacaDireccion.innerHTML.length <= 0) {
        this.$refs.refPlacaDireccion.innerHTML = ``;
    } else {
        this.$refs.refPlacaDireccion.innerHTML = ` - ${this.placaDireccion}`;
      
    }

}
