import { createBrowserRouter } from "react-router";
import { AppLayout } from "./components/layout/app-layout";
import { HomePage } from "./pages/home";
import { SearchPage } from "./pages/search";
import { DocumentPage } from "./pages/document";
import { CollectionsPage } from "./pages/collections";
import { DashboardPage } from "./pages/dashboard";
import { KnowledgeBasePage } from "./pages/knowledge-base";
import { ContactPage } from "./pages/contact";
import { ChatPage } from "./pages/chat";
import { NotFoundPage } from "./pages/not-found";

export const router = createBrowserRouter([
  {
    element: <AppLayout />,
    children: [
      { path: "/", element: <HomePage /> },
      { path: "/zoeken", element: <SearchPage /> },
      { path: "/open-overheid/documents/:id", element: <DocumentPage /> },
      { path: "/collecties", element: <CollectionsPage /> },
      { path: "/collecties/:id", element: <DocumentPage /> },
      { path: "/dashboard", element: <DashboardPage /> },
      { path: "/kennisbank", element: <KnowledgeBasePage /> },
      { path: "/contact", element: <ContactPage /> },
      { path: "/chat", element: <ChatPage /> },
      { path: "*", element: <NotFoundPage /> },
    ],
  },
]);
