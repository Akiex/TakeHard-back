import {
  BrowserRouter as Router,
  Routes,
  Route,
  Navigate,
  Outlet,
} from "react-router-dom";
import Home from "../pages/Home/Home";
import Login from "../pages/Login/Login";
import Register from "../pages/Register/Register";
import BO from "../pages/BO/BO";

const AdminRoute = () => {
  const user = JSON.parse(localStorage.getItem("user")); // Récupérer l'utilisateur stocké

  return user && user.role === "admin" ? <Outlet /> : <Navigate to="/login" />;
};
const AppRouter = () => {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/Home" element={<Home />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route element={<AdminRoute />}>
          <Route path="/BO" element={<BO />} />
        </Route>
      </Routes>
    </Router>
  );
};

export default AppRouter;
