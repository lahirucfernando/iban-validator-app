// Value required validation
export const required = (value, fieldName = "This field") => {
  return !!value || `${fieldName} is required`;
};

// Min length validation
export const minLength = (value, min) => {
  return value.length >= min || `Must be at least ${min} characters long`;
};

// Email validation
export const validateEmail = (email) => {
  if (!email) return "Email is required";
  const emailPattern = /.+@.+\..+/;
  if (!emailPattern.test(email)) return "Email must be valid";
  return true;
};

// Password confirmation validation
export const validatePasswordConfirmation = (password, confirmPassword) => {
  return confirmPassword === password ? true : "Passwords do not match";
};
