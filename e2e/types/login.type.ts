export type LoginDataType = {
  validUser: {
    username: string;
    password: string;
  };
  invalidUser: {
    username: string;
    password: string;
  };
};

export type LoginInputType = {
  username: string;
  password: string;
};
