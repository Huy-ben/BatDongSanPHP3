export function jam_read_num_forvietnamese(value = false, suffix = '') {
    const rawValue = String(value ?? '').trim();

    if (!rawValue) {
        return `0${suffix}`;
    }

    const numericValue = Number(rawValue);

    if (!Number.isFinite(numericValue)) {
        return `${rawValue}${suffix}`;
    }

    const absoluteValue = Math.trunc(Math.abs(numericValue));
    const digitsOnly = String(absoluteValue);
    const prefix = numericValue < 0 ? '-' : '';

    if (digitsOnly.length < 7) {
        return `${prefix}${absoluteValue}${suffix}`;
    }

    const formattedValue = new Intl.NumberFormat('en-US').format(absoluteValue);
    const chunks = formattedValue.split(',');
    let formattedText = `${prefix}${chunks[0]}`;

    switch (chunks.length) {
        case 4:
            formattedText = `${prefix}${chunks[0]} Tỷ`;
            if (Number(chunks[1])) {
                formattedText += ` ${chunks[1]} Triệu`;
            }
            break;
        case 3:
            formattedText = `${prefix}${chunks[0]} Triệu`;
            if (Number(chunks[1])) {
                formattedText += ` ${chunks[1]} Nghìn`;
            }
            break;
        default:
            break;
    }

    return `${formattedText}${suffix}`;
}
