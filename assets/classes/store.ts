import {
    createAsyncThunk,
    createEntityAdapter,
    createSlice,
} from "@reduxjs/toolkit";
import { Class } from "./model";
import axios from "axios";
import { RootState } from "../common/store";

const adapter = createEntityAdapter<Class>();

export const fetchClasses = createAsyncThunk("classes/fetch", async () => {
    const response = await axios.get<Class[]>("/api/classes");
    return response.data;
});

export const bookClass = createAsyncThunk(
    "classes/book",
    async (klass: Class) => {
        const response = await axios.post<Class>(
            `/api/classes/${klass.id}/book`
        );
        return response.data;
    }
);

export const cancelClass = createAsyncThunk(
    "classes/cancel",
    async (klass: Class) => {
        const response = await axios.delete<Class>(
            `/api/classes/${klass.id}/book`
        );
        return response.data;
    }
);

const slice = createSlice({
    name: "classes",
    initialState: adapter.getInitialState(),
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchClasses.fulfilled, adapter.addMany)
            .addCase(bookClass.fulfilled, adapter.upsertOne)
            .addCase(cancelClass.fulfilled, adapter.upsertOne);
    },
});

export const classesReducer = slice.reducer;

const selectors = adapter.getSelectors<RootState>((state) => state.classes);

export const getClasses = selectors.selectAll;
