
// Email validation
export const validateEmail = (email) => {
    if (!email) return 'Email is required';
    const emailPattern = /.+@.+\..+/;
    if (!emailPattern.test(email)) return 'Email must be valid';
    return true;
};

// Password validation
export const validatePassword = (password) => {
    if (!password) return 'Password is required';
    return true;
};