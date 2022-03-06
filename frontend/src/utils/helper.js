import { ElNotification } from "element-plus";

export const notification = (title, message, type) => {
    ElNotification({
        title,
        message,
        type,
        duration: 5000,
        dangerouslyUseHTMLString: true,
    });
};

export const formatNumber = (number) => {
    return new Intl.NumberFormat("ru-RU", {
        style: "currency",
        currency: "RUB",
    }).format(number);
};

export const formatVolume = (number) => {
    return number.toLocaleString();
};

export const formatPrice = (number) => {
    return number.toFixed(2);
};

export const getElementByXpath = (path) => {
    return document.evaluate(path, document, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
};

export const wordDecline = (word) => {
    const words = [
        {
            origin: "тонна",
            volume: "тонну",
        },
    ];
    for (let item in words) {
        if (words[item].origin === word) {
            return words[item].volume;
        }
    }
    return word;
};

export const formatDate = (date) => {
    const today = new Date(date * 1000);
    return today.toLocaleString("ru-RU", {
        timeZone: JSON.parse(localStorage.getItem("user_data"))?.timezone,
    });
};

export const volume = (word) => {
    const words = [
        {
            origin: "тонна",
            volume: "тонн",
        },
        {
            origin: "кубический метр",
            volume: "кубов",
        },
        {
            origin: "метр",
            volume: "метров",
        },
        {
            origin: "километр",
            volume: "километров",
        },
        {
            origin: "дюйм",
            volume: "дюймов",
        },
        {
            origin: "квадратный крилометр",
            volume: "квадратных километров",
        },
        {
            origin: "ар",
            volume: "аров",
        },
        {
            origin: "гектар",
            volume: "гекратов",
        },
        {
            origin: "квадратный метр",
            volume: "квадратных метров",
        },
        {
            origin: "квадратный дециметр",
            volume: "квадратных дециметров",
        },
        {
            origin: "квадратный сантиметр",
            volume: "квадратных сантиметров",
        },
        {
            origin: "квадратный дюйм",
            volume: "квадратных дюймов",
        },
        {
            origin: "кубический метр",
            volume: "кубических метров",
        },
        {
            origin: "кубический дециметр",
            volume: "кубических дециметров",
        },
        {
            origin: "гектолитр",
            volume: "гектолитров",
        },
        {
            origin: "килограмм",
            volume: "килограмм",
        },
        {
            origin: "грамм",
            volume: "грамов",
        },
        {
            origin: "килловат-час",
            volume: "килловат-часов",
        },
        {
            origin: "килловат",
            volume: "килловатов",
        },
    ];
    for (let item in words) {
        if (words[item].origin === word) {
            return words[item].volume;
        }
    }
    return word;
};

export const objectsEqual = (o1, o2) => {
    if (typeof o1 === "object" && Object.keys(o1).length > 0) {
        return (
            Object.keys(o1).length === Object.keys(o2).length &&
            Object.keys(o1).every((p) => objectsEqual(o1[p], o2[p]))
        );
    } else {
        return o1 === o2;
    }
};
