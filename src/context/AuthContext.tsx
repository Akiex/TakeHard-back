import { createContext, useContext, useState, useEffect, ReactNode } from "react";

interface AuthContextType {
  isConnected: boolean;
  isAdmin: boolean;
  login: (token: string, isAdmin: boolean) => void;
  logout: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [isConnected, setIsConnected] = useState(false);
  const [isAdmin, setIsAdmin] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem("token");
    if (token) {
      setIsConnected(true);
      try {
        // Décodage pour restaurer isAdmin après reload
        const decoded: any = JSON.parse(atob(token.split('.')[1]));
        setIsAdmin(decoded.role === "admin");
      } catch {}
    }
  }, []);

  const login = (token: string, admin: boolean) => {
    localStorage.setItem("token", token);
    setIsConnected(true);
    setIsAdmin(admin);
  };

  const logout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("refresh_token");
    setIsConnected(false);
    setIsAdmin(false);
    window.location.href = "/";
  };

  return (
    <AuthContext.Provider value={{ isConnected, isAdmin, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (!context) throw new Error("useAuth must be used within AuthProvider");
  return context;
};