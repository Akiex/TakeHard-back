import React, { Component, ErrorInfo } from "react";

class ErrorBoundary extends Component<
  { children: React.ReactNode },
  { hasError: boolean }
> {
  constructor(props: any) {
    super(props);
    this.state = { hasError: false };
  }

  static getDerivedStateFromError(error: Error) {
    return { hasError: true };
  }

  componentDidCatch(error: Error, errorInfo: ErrorInfo) {
    console.log("Error caught in boundary:", error, errorInfo);
  }

  render() {
    if (this.state.hasError) {
      return (
        <h2>Quelque chose s'est mal passé. Veuillez réessayer plus tard.</h2>
      );
    }

    return this.props.children;
  }
}

export default ErrorBoundary;
