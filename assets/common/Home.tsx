import { Card, CardMedia, Paper } from "@material-ui/core";
import React from "react";

const Home: React.FC = () => (
    <Card style={{ maxWidth: 400 }}>
        <CardMedia
            component="img"
            image="https://media0.giphy.com/media/Yl5aO3gdVfsQ0/giphy.gif"
            title="Nothing to see here. Please disperse."
        />
    </Card>
);

export default Home;
