export type ApiEndpointType = {
  description: string;
  url: string;
  method: 'GET' | 'POST' | 'PUT' | 'DELETE';
  bodyType: 'json' | 'form' | 'params' | 'none';
};
