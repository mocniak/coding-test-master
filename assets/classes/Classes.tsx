import { useDispatch, useSelector } from "../common/store";
import { bookClass, cancelClass, fetchClasses, getClasses } from "./store";
import React, { useEffect } from "react";
import { getUser } from "../security/store";
import { Class } from "./model";
import {
    Box,
    Button,
    Card,
    CardActions,
    CardContent,
    Grid,
    Typography,
} from "@material-ui/core";

const Classes: React.FC = () => {
    const user = useSelector(getUser);
    const classes = useSelector(getClasses);
    const dispatch = useDispatch();

    useEffect(() => {
        if (classes.length) {
            return;
        }

        void dispatch(fetchClasses());
    }, [classes.length, dispatch]);

    const isAttending = (klass: Class) =>
        klass.students.some((student) => student.id === user?.id);

    const handleBookClick = (klass: Class) => () => {
        void dispatch(bookClass(klass));
    };

    const handleCancelClick = (klass: Class) => () => {
        void dispatch(cancelClass(klass));
    };

    return (
        <Grid container spacing={2}>
            {classes.map((klass) => (
                <Grid item xs={6} md={4} lg={3} key={klass.id}>
                    <Card>
                        <CardContent>
                            <Typography variant="h5">{klass.topic}</Typography>
                            <Typography>Starts at {klass.startsAt}</Typography>
                            <Typography>Status: {klass.status}</Typography>
                        </CardContent>
                        <CardActions>
                            {isAttending(klass) && (
                                <Box>
                                    <Typography>You are attending!</Typography>
                                    <Button
                                        variant="outlined"
                                        color="default"
                                        onClick={handleCancelClick(klass)}
                                    >
                                        Cancel
                                    </Button>
                                </Box>
                            )}
                            {!isAttending(klass) &&
                                (klass.status === "scheduled" ||
                                    klass.status === "cancelled") && (
                                    <div>
                                        <Typography>
                                            You can book it!
                                        </Typography>
                                        <Button
                                            variant="contained"
                                            color="primary"
                                            onClick={handleBookClick(klass)}
                                        >
                                            Book
                                        </Button>
                                    </div>
                                )}
                        </CardActions>
                    </Card>
                </Grid>
            ))}
        </Grid>
    );
};

export default Classes;
