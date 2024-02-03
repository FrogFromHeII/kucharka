class Cookie {
    constructor(name, value, days) {
        this.name = name;
        this.value = value;
        this.days = days;
    }

    createCookie() {
        let expires = "";
        if (this.days) {
            let date = new Date();
            date.setTime(date.getTime() + (this.days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = this.name + "=" + (this.value || "")  + expires + "; path=/";
    }

    extendCookie() {
        this.createCookie();
    }

    checkCookie() {
        let nameEQ = this.name + "=";
        let ca = document.cookie.split(';');
        for(let i=0;i < ca.length;i++) {
            let c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return true;
        }
        return false;
    }

    alertUser() {
        alert("Tato webová stránka používá cookies k vylepšení uživatelského zážitku.");
    }
}