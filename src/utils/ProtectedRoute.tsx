import { Navigate, Outlet } from "react-router-dom";
import { jwtDecode } from "jwt-decode";

const isTokenValid = () => {
  const token = localStorage.getItem("token");
  if (!token) return false;

  try {
    const decoded: { exp: number } = jwtDecode(token);
    return decoded.exp * 1000 > Date.now();
  } catch (error) {
    return false;
  }
};

const ProtectedRoute = () => {
  return isTokenValid() ? <Outlet /> : <Navigate to="/" />;
};

export default ProtectedRoute;

