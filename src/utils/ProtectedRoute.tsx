import { Navigate, Outlet, useLocation, useParams } from "react-router-dom";
import { jwtDecode } from "jwt-decode";

interface DecodedToken {
  user_id: number;
  role: string;
  exp: number;
}

const ProtectedRoute = () => {
  const location = useLocation();
  const params = useParams<{ id?: string }>();
  console.log("🔒 ProtectedRoute for", location.pathname, "params:", params);

  const token = localStorage.getItem("token");
  if (!token) {
    console.log("→ No token → redirect to /");
    return <Navigate to="/" replace />;
  }

  let decoded: DecodedToken;
  try {
    decoded = jwtDecode<DecodedToken>(token);
  } catch {
    console.log("→ Invalid token → redirect to /");
    return <Navigate to="/" replace />;
  }

  const now = Date.now();
  if (decoded.exp * 1000 <= now) {
    console.log("→ Token expired → redirect to /");
    return <Navigate to="/" replace />;
  }

  // Check admin-only
  if (location.pathname === "/bo" && decoded.role !== "admin") {
    console.log("→ Non-admin on /bo → redirect to /");
    return <Navigate to="/" replace />;
  }

  // Check owner-only
  if (params.id) {
    const routeId = Number(params.id);
    if (routeId !== decoded.user_id) {
      console.log(`→ Bloqué : id URL ${routeId} ≠ token ${decoded.user_id}`);
      return <Navigate to="/" replace />;
    }
  }

  console.log("→ Access granted");
  return <Outlet />;
};

export default ProtectedRoute;
