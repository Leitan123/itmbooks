import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  basePath: '/itmbooks.com',
  async redirects() {
    return [
      {
        source: '/',
        destination: '/itmbooks.com/login',
        basePath: false,
        permanent: false,
      },
    ];
  },
};

export default nextConfig;

