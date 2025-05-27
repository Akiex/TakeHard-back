import {
  createContext,
  useContext,
  useState,
  useEffect,
  ReactNode,
} from "react";

interface AuthContextType {
  userId: number | null;
  isConnected: boolean;
  isAdmin: boolean;
  login: (token: string, isAdmin: boolean) => void;
  logout: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [isConnected, setIsConnected] = useState(false);
  const [isAdmin, setIsAdmin] = useState(false);
  const [userId, setUserId] = useState<number | null>(null);

  // AuthContext.tsx
  useEffect(() => {
    const token = localStorage.getItem("token");
    console.log("AuthProvider useEffect, token:", token);
    if (token) {
      setIsConnected(true);
      try {
        const decoded: any = JSON.parse(atob(token.split(".")[1]));
        console.log("Decoded JWT in useEffect:", decoded);
        setIsAdmin(decoded.role === "admin");
        setUserId(decoded.user_id);
      } catch (e) {
        console.error(e);
      }
    }
  }, []);

  const login = (token: string, admin: boolean) => {

    localStorage.setItem("token", token);
    setIsConnected(true);
    setIsAdmin(admin);
    const decoded: any = JSON.parse(atob(token.split(".")[1]));
    setUserId(decoded.user_id);
  };

  const logout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("refresh_token");
    setUserId(null);
    setIsConnected(false);
    setIsAdmin(false);
    window.location.href = "/";
  };

  return (
    <AuthContext.Provider
      value={{ userId, isConnected, isAdmin, login, logout }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (!context) throw new Error("useAuth must be used within AuthProvider");
  return context;
};
