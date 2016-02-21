Notes:

- Many places in the code assume a single role per user/account
- Many places in the code only looks at whether a person is a member of an account instead of checking the actual permissions the person has.  This is likely causing some possible privilege escalation.


