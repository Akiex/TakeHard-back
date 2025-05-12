import React from "react";

interface AsyncStateProps {
  loading: boolean;
  error: string | null;
  loadingText?: string;
  children: React.ReactNode;
}

const AsyncState = ({ loading, error, loadingText = "Loading...", children }: AsyncStateProps) => {
  if (loading) return <p>{loadingText}</p>;
  if (error) return <p className="error">{error}</p>;

  return <>{children}</>;
};

export default AsyncState;