import React from "react";
import { Routes, Route, Navigate, Outlet } from "react-router-dom";
import Home from "../pages/Home/Home";
import Login from "../pages/Login/Login";
import Register from "../pages/Register/Register";
import BO from "../pages/BO/BO";
import ProtectedRoute from "../utils/ProtectedRoute";
import AccountPage from "../pages/Profile/AccountPage";

// Garde uniquement la logique d'accès admin ici
const AdminRoute = () => {
  const user = JSON.parse(localStorage.getItem("user"));
  return user?.role === "admin" ? <Outlet /> : <Navigate to="/login" />;
};

const AppRoutes = () => (
  <Routes>
    <Route path="/" element={<Home />} />
    <Route path="/home" element={<Home />} />
    <Route path="/login" element={<Login />} />
    <Route path="/register" element={<Register />} />
    <Route path="/accountpage/:id" element={<AccountPage />} />
    <Route element={<ProtectedRoute />}>
      <Route path="/BO" element={<BO />} />
    </Route>
    <Route path="*" element={<Navigate to="/" replace />} />
  </Routes>
);

export default AppRoutes;
